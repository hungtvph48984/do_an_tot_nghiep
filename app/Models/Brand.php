<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'website',
        'email',
        'phone',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with products
    public function products()
    {
        return $this->hasMany(Product::class);
        // Hoặc nếu bạn sử dụng tên khác:
        // return $this->hasMany(Product::class, 'brand_id');
    }

    // Accessor for logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return Storage::disk('public')->url($this->logo);
        }
        
        return asset('admin/images/brand-placeholder.png'); // hoặc placeholder khác
    }

    // Scope for active brands
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}