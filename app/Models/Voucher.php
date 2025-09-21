<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',       // fixed | percent
        'sale_price', // giá trị giảm (VND hoặc %)
        'min_order',
        'max_price',  // giảm tối đa (nếu percent)
        'quantity',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'sale_price' => 'decimal:2',
        'min_order'  => 'decimal:2',
        'max_price'  => 'decimal:2',
    ];

    public function isActive(): bool
    {
        return $this->quantity > 0
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    public function isValidForOrder(float $orderAmount): bool
    {
        return $this->isActive() && $orderAmount >= $this->min_order;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->isValidForOrder($orderAmount)) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->sale_price, $orderAmount);
        }

        // percent
        $discount = ($orderAmount * $this->sale_price) / 100;
        if ($this->max_price) {
            $discount = min($discount, $this->max_price);
        }

        return min($discount, $orderAmount);
    }
}
