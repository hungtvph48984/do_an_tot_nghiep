<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'base_price'];

    public function variants()
    {
        return $this->hasMany(Variant::class);
=======
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
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }

    public function category()
    {
<<<<<<< HEAD
        return $this->belongsTo(Category::class);
    }

}
=======
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getImageUrlAttribute()
{
    if (!$this->image) return null;
    return Storage::url(is_array($this->image) ? $this->image[0] : $this->image);
}

}
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
