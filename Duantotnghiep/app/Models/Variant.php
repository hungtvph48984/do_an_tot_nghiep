<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = ['product_id', 'attributes', 'price', 'stock', 'sku'];
    protected $casts = ['attributes' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}