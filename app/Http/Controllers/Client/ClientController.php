<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('client.index');
    }

     public function about()
    {
        return view('client.about');
    }
     public function index_2()
    {
        return view('client.index-2');
    }
    public function contact()
    {
        return view('client.contact');
    }
}

