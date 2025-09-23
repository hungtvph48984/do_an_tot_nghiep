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
        'status',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** ================== RELATIONSHIPS ================== */
    // 1 thương hiệu có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /** ================== ACCESSORS ================== */
    // Lấy link đầy đủ của logo
    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? Storage::disk('public')->url($this->logo)
            : asset('admin/images/brand-placeholder.png'); // ảnh mặc định
    }

    /** ================== SCOPES ================== */
    // Chỉ lấy brand active
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
