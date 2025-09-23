<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'email',
        'status',
        'payment_method',
        'payment_status', // Thêm vào fillable
        'total',
        'subtotal',
        'shipping_fee',
        'vorcher_code',
        'sale_price',
        'pay_amount',
        'note',
        'province_id',
        'district_id', 
        'ward_code'
    ];

    protected $casts = [
        'status' => 'integer',
        'payment_method' => 'integer',
        'payment_status' => 'integer', // Thêm cast
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'pay_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---------------------- Relationships ----------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    // ---------------------- Constants ----------------------
    const STATUS_PENDING    = 0;
    const STATUS_CONFIRMED  = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SHIPPING   = 3;
    const STATUS_DELIVERED  = 4;
    const STATUS_CANCELLED  = 5;
    const STATUS_RETURNED   = 6;

    const PAYMENT_COD  = 0;
    const PAYMENT_MOMO = 1;
    const PAYMENT_BANK = 2;

    // Thêm payment status constants
    const PAYMENT_STATUS_PENDING = 0;    // Chưa thanh toán
    const PAYMENT_STATUS_PAID    = 1;    // Đã thanh toán
    const PAYMENT_STATUS_FAILED  = 2;    // Thanh toán thất bại
    const PAYMENT_STATUS_REFUNDED = 3;   // Đã hoàn tiền

    // ---------------------- Options ----------------------
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING    => 'Chờ xử lý',
            self::STATUS_CONFIRMED  => 'Đã xác nhận',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_SHIPPING   => 'Đang giao hàng',
            self::STATUS_DELIVERED  => 'Đã giao',
            self::STATUS_CANCELLED  => 'Đã hủy',
            self::STATUS_RETURNED   => 'Đã trả hàng',
        ];
    }

    public static function getPaymentOptions()
    {
        return [
            self::PAYMENT_COD  => 'COD',
            self::PAYMENT_MOMO => 'MOMO',
            self::PAYMENT_BANK => 'Chuyển khoản',
        ];
    }

    // Thêm payment status options
    public static function getPaymentStatusOptions()
    {
        return [
            self::PAYMENT_STATUS_PENDING  => 'Chưa thanh toán',
            self::PAYMENT_STATUS_PAID     => 'Đã thanh toán',
            self::PAYMENT_STATUS_FAILED   => 'Thanh toán thất bại',
            self::PAYMENT_STATUS_REFUNDED => 'Đã hoàn tiền',
        ];
    }

    // ---------------------- Accessors ----------------------
    public function getStatusTextAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? 'Không xác định';
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING    => 'badge-secondary',
            self::STATUS_CONFIRMED  => 'badge-primary',
            self::STATUS_PROCESSING => 'badge-info',
            self::STATUS_SHIPPING   => 'badge-warning',
            self::STATUS_DELIVERED  => 'badge-success',
            self::STATUS_CANCELLED  => 'badge-danger',
            self::STATUS_RETURNED   => 'badge-dark',
            default => 'badge-light',
        };
    }

    public function getPaymentTextAttribute()
    {
        return self::getPaymentOptions()[$this->payment_method] ?? 'Không xác định';
    }

    // Thêm payment status text accessor
    public function getPaymentStatusTextAttribute()
    {
        return self::getPaymentStatusOptions()[$this->payment_status] ?? 'Không xác định';
    }

    // Thêm payment status badge class
    public function getPaymentStatusBadgeClassAttribute()
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_PENDING  => 'badge-warning',
            self::PAYMENT_STATUS_PAID     => 'badge-success',
            self::PAYMENT_STATUS_FAILED   => 'badge-danger',
            self::PAYMENT_STATUS_REFUNDED => 'badge-info',
            default => 'badge-secondary',
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.') . 'đ';
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal ?? 0, 0, ',', '.') . 'đ';
    }

    public function getFormattedShippingFeeAttribute()
    {
        return number_format($this->shipping_fee ?? 0, 0, ',', '.') . 'đ';
    }

    // ---------------------- Scopes ----------------------
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('id', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    // ---------------------- Boot ----------------------
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!isset($order->status)) {
                $order->status = self::STATUS_PENDING;
            }

            if (!isset($order->payment_status)) {
                $order->payment_status = self::PAYMENT_STATUS_PENDING;
            }

            if (empty($order->pay_amount)) {
                $order->pay_amount = $order->total - ($order->sale_price ?? 0);
            }

            if (empty($order->subtotal) && !empty($order->total)) {
                $order->subtotal = $order->total - ($order->shipping_fee ?? 0);
            }
        });
    }
}