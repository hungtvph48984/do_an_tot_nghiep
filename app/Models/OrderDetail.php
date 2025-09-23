<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'order_id',
        'price',
        'quantity',
    ];

    // 1 đơn hàng chi tiết thuộc về 1 đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 1 chi tiết đơn hàng thuộc về 1 biến thể sản phẩm
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Lấy sản phẩm thông qua productVariant
    public function product()
    {
        return $this->productVariant->product();
    }

    // Lấy size thông qua productVariant
    public function size()
    {
        return $this->productVariant->size();
    }

    // Lấy màu thông qua productVariant
    public function color()
    {
        return $this->productVariant->color();
    }
}
