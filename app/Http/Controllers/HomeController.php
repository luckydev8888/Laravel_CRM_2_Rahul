<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', ['nocontact' => Client::whereRaw('lower(status) like (?)',["%no contact%"])->count(), 
                            'cockroach' => Client::whereRaw('lower(status) like (?)',["%cockroach%"])->count(),
                            'not_interesting' => Client::whereRaw('lower(status) like (?)',["%not interesting%"])->count(),
                            'not_interested' => Client::whereRaw('lower(status) like (?)',["%not interested%"])->count(),
                            'interested' => Client::whereRaw('lower(status) like (?)',["%interested"])->count(),
                            'calls' => Client::whereRaw('lower(status) like (?)',["%call%"])->count(),
                            'standby' => Client::whereRaw('lower(status) like (?)',["%standby%"])->count(),
                            'almost' => Client::whereRaw('lower(status) like (?)',["%almost%"])->count(),
                            'customer' => Client::whereRaw('lower(status) like (?)',["%customer%"])->count()]);
    }
}
