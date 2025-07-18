<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Hiển thị trang thanh toán
    public function show()
    {
        return view('clients.checkout.show');
    }
}
