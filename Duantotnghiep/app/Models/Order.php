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
        'payment_method',   // ✅ sửa lại cho rõ nghĩa
        'payment_status',
        'total',
        'vorcher_code',
        'sale_price',
        'pay_amount',
    ];

    // ====== PHƯƠNG THỨC THANH TOÁN ======
    const PAYMENT_METHOD_COD = 'cod';
    const PAYMENT_METHOD_ONLINE = 'online';

    // ====== TRẠNG THÁI THANH TOÁN ======
    const STATUS_UNPAID  = 'unpaid';
    const STATUS_PAID    = 'paid';
    const STATUS_FAILED  = 'failed';

    /**
     * Quan hệ: Mỗi đơn hàng thuộc về một user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
