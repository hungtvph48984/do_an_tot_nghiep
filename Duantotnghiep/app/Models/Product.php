<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'description',
        'metarial',
        'instrut',
        'status',
        'category_id',
    ];
  //Quan hệ 1 - n (từ bảng con n - 1)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function getLowestPriceAttribute()
    {
        return $this->variants()->min('price');
    }

}
