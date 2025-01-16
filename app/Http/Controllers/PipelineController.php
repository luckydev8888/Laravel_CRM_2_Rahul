<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;


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

}
