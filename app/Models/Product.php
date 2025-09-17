<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'image', 'description', 'metarial', 'instrut', 'status', 'category_id'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        return Storage::url(is_array($this->image) ? $this->image[0] : $this->image);
    }

}
