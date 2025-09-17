<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{
    public function show()
    {
        $orders = Auth::user()->orders()->latest()->get();
        return view('client.profile', compact('orders'));
    }

}

