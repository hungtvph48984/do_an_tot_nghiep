<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vorcher extends Model
{
      use HasFactory;

    protected $fillable =
    [
        'name',
        'code',
        'type',
        'sale_price',
        'min_order',
        'max_price',
        'quantity',
        'start_date',
        'end_date',
    ];
}
