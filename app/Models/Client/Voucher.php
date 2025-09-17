<?php
namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Voucher extends Model
{
    protected $fillable = ['code', 'discount_type', 'discount_value', 'expires_at'];

    protected $dates = ['expires_at'];

    public function isValid()
    {
        return !$this->expires_at || Carbon::now()->lt($this->expires_at);
    }
}
