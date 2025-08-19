<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'image', 'description', 'metarial', 'instrut', 'status', 'category_id', 'images'];
        protected $casts = [
            'images' => 'array',
        ];
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

}

