<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
