<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderStatus;
use App\Models\Payment;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at'
    ];

    protected $casts = [
        'products' => 'array',
        'address' => 'array'
    ];

    public function orderStatus() {
        return $this->hasOne(OrderStatus::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
