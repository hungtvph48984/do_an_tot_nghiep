<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    //hiển thị giỏ hàng
    public function show()
    {
        return view('clients.cart.show');
    }
}
