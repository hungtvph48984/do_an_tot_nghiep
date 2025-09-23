<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'image',
        'description',
        'metarial',
        'instrut',
        'status',
        'category_id',
        'brand_id',  // ✅ thêm brand_id
        'images',    
        'price',     
    ];

    protected $casts = [
        'images' => 'array',   // album ảnh -> array
        'status' => 'boolean',
    ];

    /** ================== RELATIONSHIPS ================== */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /** ================== ACCESSORS ================== */
    // Ảnh đại diện
    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        return Storage::url(is_array($this->image) ? $this->image[0] : $this->image);
    }

    // Album ảnh - trả về URL đầy đủ
    public function getAlbumUrlsAttribute(): array
    {
        $imgs = $this->images ?? [];
        if (!is_array($imgs)) {
            $decoded = json_decode($imgs, true);
            $imgs = is_array($decoded) ? $decoded : [];
        }
        return array_map(fn($p) => Storage::url($p), $imgs);
    }

    // Giá min dựa theo giá hiệu lực variant
    public function getMinPriceAttribute(): ?float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        if ($variants->isEmpty()) return null;
        return $variants->min(fn($v) => $v->effective_price);
    }

    // Giá max dựa theo giá hiệu lực variant
    public function getMaxPriceAttribute(): ?float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        if ($variants->isEmpty()) return null;
        return $variants->max(fn($v) => $v->effective_price);
    }
}
