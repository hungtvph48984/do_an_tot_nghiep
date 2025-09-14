<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'email',
        'status',
        'payment_method',
        'total',
        'vorcher_code',
        'sale_price',
        'pay_amount',
        'note'
    ];

    protected $casts = [
        'status' => 'integer',
        'payment_method' => 'integer',
        'total' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'pay_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SHIPPING = 3;
    const STATUS_DELIVERED = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_RETURNED = 6;

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_SHIPPING => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao',
            self::STATUS_CANCELLED => 'Đã hủy',
            self::STATUS_RETURNED => 'Đã trả hàng',
        ];
    }

    // Payment method options
    const PAYMENT_COD = 0;
    const PAYMENT_MOMO = 1;

    public static function getPaymentOptions()
    {
        return [
            self::PAYMENT_COD => 'COD',
            self::PAYMENT_MOMO => 'MOMO',
        ];
    }

    public function getStatusTextAttribute()
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? 'Không xác định';
    }

    public function getPaymentTextAttribute()
    {
        $payments = self::getPaymentOptions();
        return $payments[$this->payment_method] ?? 'Không xác định';
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!isset($order->status)) {
                $order->status = self::STATUS_PENDING;
            }

            if (empty($order->pay_amount)) {
                $order->pay_amount = $order->total - ($order->sale_price ?? 0);
            }
        });
    }
}
