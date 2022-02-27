<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderController extends Controller {
    function index() {}

    function shipmentLocator() {}

    function dashboard() {}

    function create(Request $request) {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid();
            $payload['order_status_id'] = OrderStatus::where('uuid', $payload['order_status_uuid'])->value('id');
            $payload['payment_id'] = Payment::where('uuid', $payload['payment_uuid'])->value('id');
            Order::create(Arr::only(
                $payload,
                [
                    'user_id',
                    'order_status_id',
                    'payment_id',
                    'products',
                    'address',
                    'delivery_fee',
                    'amount',
                    'shipped_at'
                ])
            );
            return response(null, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
    
    function show($uuid) {}
    
    function update() {}
    
    function delete() {}

    function download() {}
}
