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
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'image', 'description', 'metarial', 'instrut',
        'status', 'category_id',
        'images',    // ✅ thêm
        'price',     // ✅ thêm (nếu bạn giữ price ở bảng products)
    ];

    protected $casts = [
        'images' => 'array',   // ✅ album ảnh thành mảng
        'status' => 'boolean',
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

    // Giá min–max dựa trên giá hiệu lực của variant (sale_price nếu có, không thì price)
    public function getMinPriceAttribute(): ?float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        if ($variants->isEmpty()) return null;
        return $variants->min(fn($v) => $v->effective_price);
    }

    public function getMaxPriceAttribute(): ?float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        if ($variants->isEmpty()) return null;
        return $variants->max(fn($v) => $v->effective_price);
    }
}
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
