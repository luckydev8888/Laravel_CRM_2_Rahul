<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class ClientController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('contacts', ['users' => User::all()]);
    }

    public function deleteContact(Request $req) {
        // var_dump($req->input('ids'));
        Client::whereIn('id', $req->input('ids'))->delete();
        return response('success', 200);
    }

    public function newContact(Request $req) {
        $req->validate([
            'tel1' => 'nullable|unique:clients,tel1',
            'tel2' => 'nullable|unique:clients,tel2',
        ]);
    
        Client::create($req->all());
        return redirect('/contacts')->with('success', 'Contact added successfully.');
    }

    public function editcolumn(Request $req) {
        $columns = ['id', 'assigned', 'id', 'date', 'status', 'score', 'company', 'contact', 'tel1', 'tel2', 'town',
                    'area', 'samples', 'display', 'prices', 'brand', 'comments'];
        Client::where('id', $req->input('id'))->update( [$columns[$req->input('column')] => $req->input('data')] );
        return response('success', 200);
    }

    public function edit(Request $req) {
        $req->validate([
            'tel1' => 'nullable|unique:clients,tel1,' . $req->input('id'),
            'tel2' => 'nullable|unique:clients,tel2,' . $req->input('id'),
        ]);
    
        Client::where('id', $req->input('id'))->update([
            'country' => $req->input('country'),
            'status' => $req->input('status'),
            'date' => $req->input('date'),
            'company' => $req->input('company'),
            'tel1' => $req->input('tel1'),
            'tel2' => $req->input('tel2'),
            'contact' => $req->input('contact'),
            'town' => $req->input('town'),
            'area' => $req->input('area'),
            'brand' => $req->input('brand'),
            'score' => $req->input('score'),
            'samples' => $req->input('samples'),
            'prices' => $req->input('prices'),
            'display' => $req->input('display'),
            'comments' => $req->input('comments'),
            'email' => $req->input('email'),
        ]);
    
        return redirect('/contacts')->with('success', 'Contact updated successfully.');
    }

    public function whatsapp() {
        $fileName = 'whatsapp.csv';
        $tasks = Client::whereIn('status', ['Call 1', 'Call 2', 'Call 3', 'Standby', 'Almost', 'Customer'])
            ->get();
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($tasks) {
            $file = fopen('php://output', 'w');

            foreach ($tasks as $task) {
                $row['tel2']  = $task->tel2;

                fputcsv($file, array($row['tel2']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function get_list(Request $request) {
        $columns = ['id', 'assigned', 'id', 'date', 'status', 'score', 'company', 'contact', 'tel1', 'tel2', 'town',
                    'area', 'samples', 'display', 'prices', 'brand', 'comments'];
        $totalData = Client::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $columnDatas = $request->input('columns');
        $country = $request->input('country', '');

        if ($limit == -1) $limit = 1844674407370955161;
        $search = $request->input('search.value', '');

        if ($columnDatas[12]['search']['value'] == 'Yes') $columnDatas[12]['search']['value'] = '1';

        if ($columnDatas[12]['search']['value'] == 'No') $columnDatas[12]['search']['value'] = '0';

        if ($columnDatas[13]['search']['value'] == 'Yes') $columnDatas[12]['search']['value'] = '1';

        if ($columnDatas[13]['search']['value'] == 'No') $columnDatas[12]['search']['value'] = '0';

        if ($columnDatas[14]['search']['value'] == 'Yes') $columnDatas[12]['search']['value'] = '1';

        if ($columnDatas[14]['search']['value'] == 'No') $columnDatas[12]['search']['value'] = '0';


        $clients = Client::where(function (Builder $q) use ($search) {
                        $q->orWhere('id','LIKE',"%{$search}%")
                        ->orWhere('company', 'LIKE',"%{$search}%")
                        ->orWhere('contact', 'LIKE',"%{$search}%")
                        ->orWhere('tel1', 'LIKE',"%{$search}%")
                        ->orWhere('tel2', 'LIKE',"%{$search}%")
                        ->orWhere('town', 'LIKE',"%{$search}%")
                        ->orWhere('area', 'LIKE',"%{$search}%")
                        ->orWhere('brand', 'LIKE',"%{$search}%")
                        ->orWhere('comments', 'LIKE',"%{$search}%");
                    })->where(function (Builder $q) use ($columnDatas, $columns, $country) {
                        foreach ($columnDatas as $key => $data) {
                            if ($data['search']['value']) {
                                $q->where($columns[$key], 'LIKE', "%".$data['search']['value']."%");
                            }
                        }
                        if($country != '') {
                            $q->where('country', 'LIKE', '%'.$country.'%');
                        }
                    })->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

        $totalFiltered = Client::where(function (Builder $q) use ($search) {
                            $q->orWhere('id','LIKE',"%{$search}%")
                            ->orWhere('company', 'LIKE',"%{$search}%")
                            ->orWhere('contact', 'LIKE',"%{$search}%")
                            ->orWhere('tel1', 'LIKE',"%{$search}%")
                            ->orWhere('tel2', 'LIKE',"%{$search}%")
                            ->orWhere('town', 'LIKE',"%{$search}%")
                            ->orWhere('area', 'LIKE',"%{$search}%")
                            ->orWhere('brand', 'LIKE',"%{$search}%")
                            ->orWhere('comments', 'LIKE',"%{$search}%");
                        })->where(function (Builder $q) use ($columnDatas, $columns, $country) {
                            foreach ($columnDatas as $key => $data) {
                                if ($data['search']['value']) {
                                    $q->where($columns[$key], 'LIKE', "%".$data['search']['value']."%");
                                }
                            }
                        if($country != '') {
                            $q->where('country', 'LIKE', '%'.$country.'%');
                        }
                        })->count();

        $data = array();
        if(!empty($clients))
        {
            foreach ($clients as $client)
            {
                $nestedData['id'] = $client->id;
                $nestedData['assigned'] = $client->assigned;
                $nestedData['key'] = $client->id;
                $nestedData['date'] = $client->date;
                $nestedData['status'] = $client->status;
                $nestedData['score'] = $client->score;
                $nestedData['company'] = $client->company;
                $nestedData['contact'] = $client->contact;
                $nestedData['tel1'] = $client->tel1;
                $nestedData['tel2'] = $client->tel2;
                $nestedData['town'] = $client->town;
                $nestedData['area'] = $client->area;
                $nestedData['samples'] = $client->samples ? 'Yes' : 'No';
                $nestedData['display'] = $client->display ? 'Yes' : 'No';
                $nestedData['prices'] = $client->prices ? 'Yes' : 'No';
                $nestedData['brand'] = $client->brand;
                $nestedData['comments'] = $client->comments;
                $nestedData['emp'] = '';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        echo json_encode($json_data); 
    }

    public function checkPhone(Request $req) {
        $phone = $req->input('phone');
        $id = $req->input('id'); // Exclude current record for edit
    
        $exists = Client::where(function($query) use ($phone) {
            $query->where('tel1', $phone)->orWhere('tel2', $phone);
        });
    
        if ($id) {
            $exists->where('id', '!=', $id); // Exclude current record for edit
        }
    
        return response()->json(['exists' => $exists->exists()]);
    }
    public function updateDate(Request $request){
        $request->validate([
            'id' => 'required|exists:clients,id',
            'date' => 'required|date_format:Y-m-d', // Ensure the date is valid
        ]);

        // Find the client and update the date
        $client = Client::find($request->input('id'));
        $client->date = $request->input('date');
        $client->save();

        return response()->json(['success' => true, 'message' => 'Date updated successfully']);
    }
}
