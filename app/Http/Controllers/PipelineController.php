<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailWithAttachment;
use App\Services\WhatsAppService;


class PipelineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        // Define default statuses
        $defaultStatuses = ['No contact', 'Call 1', 'Send Sample', 'Sample Testing', 'Standby', 'Almost'];

        // Fetch clients grouped by status
        $clients = Client::whereIn('status', $defaultStatuses)->get();

        // Format the date for each client
        $clients->each(function ($client) {
            $client->formatted_date = $client->date ? Carbon::parse($client->date)->format('d M Y') : null;
        });

        // Group clients by their status and ensure keys match $defaultStatuses
        $statuses = collect($defaultStatuses)
            ->mapWithKeys(function ($status) use ($clients) {
                return [$status => $clients->where('status', $status)];
            });

        // Filter by city and search if applicable
        $cities = Client::distinct()->pluck('town');
        $selectedCity = $request->get('city', '');
        $searchQuery = $request->get('search', '');

        if ($selectedCity) {
            $statuses = $statuses->map(function ($contacts) use ($selectedCity) {
                return $contacts->filter(function ($contact) use ($selectedCity) {
                    return $contact->town === $selectedCity;
                });
            });
        }

        if ($searchQuery) {
            $statuses = $statuses->map(function ($contacts) use ($searchQuery) {
                return $contacts->filter(function ($contact) use ($searchQuery) {
                    return stripos($contact->contact, $searchQuery) !== false ||
                        stripos($contact->tel1, $searchQuery) !== false ||
                        stripos($contact->company, $searchQuery) !== false;
                });
            });
        }

        return view('pipeline', [
            'statuses' => $statuses,
            'cities' => $cities,
            'selectedCity' => $selectedCity,
            'searchQuery' => $searchQuery,
            'users' => User::all()
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:clients,id',
            'direction' => 'required|in:left,right',
        ]);

        $contactIds = $request->input('contact_ids');
        $contacts = Client::whereIn('id', $contactIds)->get();
        $statuses = ['No contact', 'Call 1', 'Send Sample', 'Sample Testing', 'Standby', 'Almost'];

        foreach ($contacts as $contact) {
            $currentIndex = array_search($contact->status, $statuses);

            // Determine new status
            if ($request->input('direction') === 'left' && $currentIndex > 0) {
                $contact->status = $statuses[$currentIndex - 1];
            } elseif ($request->input('direction') === 'right' && $currentIndex < count($statuses) - 1) {
                $contact->status = $statuses[$currentIndex + 1];
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot move further']);
            }

            $contact->save();
        }

        return response()->json(['success' => true, 'message' => 'Contacts moved successfully']);
    }

    public function getdata($id){
        $contact = Client::find($id);

        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        return response()->json($contact);
    }

    public function edit(Request $request){
        // Validate the request data
        $request->validate([
            'id' => 'required|exists:clients,id',
            'contact' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'assigned' => 'nullable|exists:users,id',
            'status' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'company' => 'nullable|string|max:255',
            'tel1' => 'nullable|string|max:255',
            'tel2' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'area' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'score' => 'nullable|string|max:1',
            'samples' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
        ]);

        // Find the client by ID
        $client = Client::findOrFail($request->id);

        // Update the client record
        $client->update([
            'contact' => $request->contact,
            'country' => $request->country,
            'assigned' => $request->assigned,
            'status' => $request->status,
            'date' => $request->date,
            'company' => $request->company,
            'tel1' => $request->tel1,
            'tel2' => $request->tel2,
            'town' => $request->town,
            'email' => $request->email,
            'area' => $request->area,
            'brand' => $request->brand,
            'score' => $request->score,
            'samples' => $request->samples,
            'comments' => $request->comments,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Contact updated successfully!');
    }

    public function updateStatus(Request $request){
        // Validate the request data
        $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:clients,id',
            'status' => 'required|string|max:255',
        ]);

        $contactIds = $request->input('contact_ids');
        $status = $request->input('status');

        // Update the status for the selected contacts
        Client::whereIn('id', $contactIds)->update(['status' => $status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
    }

    public function sendEmail(Request $request){
        $request->validate([
            'to_email' => 'required|email',  // Validate "To Email"
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // Max file size: 10MB
        ]);

        $emailData = [
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        // Initialize variables for file attachment
        $filePath = null;
        $fileName = null;
        $mime = null;

        // Check if an attachment is provided
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->getRealPath();
            $fileName = $request->file('attachment')->getClientOriginalName();
            $mime = $request->file('attachment')->getMimeType();
        }

        try {
            // Send the email with or without attachment
            Mail::to($request->to_email)->send(new EmailWithAttachment($emailData, $filePath, $fileName, $mime));

            return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }


    public function updateDate(Request $request) {
        $request->validate([
            'contact_id' => 'required|exists:clients,id',
            'date' => 'required|date',
        ]);
    
        $contact = Client::find($request->contact_id);
    
        if (!$contact) {
            return response()->json(['success' => false, 'message' => 'Contact not found.']);
        }
    
        $contact->date = $request->date;
        $contact->save();
    
        return response()->json(['success' => true, 'message' => 'Date updated successfully!']);
    }

    private function formatPhoneNumber($number) {
        // Remove all non-numeric characters except '+'
        $number = preg_replace('/[^\d+]/', '', $number);
    
        // Add '+' if it doesn't exist
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }
    
        return $number;
    }

    public function sendWhatsapp(Request $request) {
        $request->validate([
            'from_whatsapp' => 'required|string',
            'to_whatsapp' => 'required|string',
            'whatsapp_subject' => 'nullable|string|max:255',
            'whatsapp_message' => 'required|string',
            'whatsapp_attachment' => 'nullable|file|max:10240',
        ]);
    
        $fromWhatsapp = $this->formatPhoneNumber($request->input('from_whatsapp'));
        $toWhatsapp = $this->formatPhoneNumber($request->input('to_whatsapp'));
        $message = $request->input('whatsapp_message');
        $attachment = $request->file('whatsapp_attachment');
    
        $whatsappService = new WhatsAppService();
        $result = $whatsappService->sendMessage($fromWhatsapp, $toWhatsapp, $message, null, $attachment);
    
        if ($result['success']) {
            return response()->json(['success' => true, 'message' => 'WhatsApp message sent successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => $result['error'] ?? 'Failed to send message.']);
        }
    }

    public function editInline(Request $request, $id){
        $request->validate([
            'field' => 'required|string',
            'value' => 'nullable|string|max:255',
        ]);

        $contact = Client::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');

        // Ensure the field is allowed for editing
        $allowedFields = ['contact', 'company', 'tel1', 'tel2'];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field']);
        }

        $contact->$field = $value;
        $contact->save();

        return response()->json(['success' => true, 'message' => 'Field updated successfully']);
    }

}
