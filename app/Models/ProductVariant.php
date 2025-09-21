<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'sku',         // ✅ thêm
        'price',
        'sale_price',  // ✅ đổi từ 'sale' -> 'sale_price'
        'stock',
        'image',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'stock' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Giá hiệu lực (ưu tiên sale_price nếu > 0)
    public function getEffectivePriceAttribute(): float
    {
        $sp = $this->sale_price ?? 0.0;
        return $sp > 0 ? $sp : (float) $this->price;
    }

    // app/Models/ProductVariant.php
public function setPriceAttribute($value)
{
    $this->attributes['price'] = max(0, $value);
}

public function setSalePriceAttribute($value)
{
    $this->attributes['sale_price'] = $value !== null ? max(0, $value) : null;
}

}
