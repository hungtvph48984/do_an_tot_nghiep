<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'email',
        'note',
        'status',
        'payment_method',
        'total',
        'vorcher_code',
        'sale_price',
        'pay_amount'
    ];
}

