<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller {
    function index() {}

    function shipmentLocator() {}

    function dashboard() {}

    function create(Request $request) {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid();
            $payload['user_id'] = 1; // @todo
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
    
    function show($uuid) {
        $order = Order::where('uuid', $uuid)->first();
        return response()->json($order, 200);
    }
    
    function update($uuid, Request $request) {
        try {
            $payload = $request->post();
            $payload['user_id'] = 1; // @todo
            $payload['order_status_id'] = OrderStatus::where('uuid', $payload['order_status_uuid'])->value('id');
            $payload['payment_id'] = Payment::where('uuid', $payload['payment_uuid'])->value('id');
            Order::where('uuid', $uuid)
                ->update(
                    Arr::only($request->post(), [
                        'user_id',
                        'order_status_id',
                        'payment_id',
                        'uuid',
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
    
    function delete($uuid) {
        try {
            Order::where('uuid', $uuid)
                ->delete();
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function download() {}
}
