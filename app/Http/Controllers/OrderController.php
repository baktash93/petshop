<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller {
    function index(Request $request) {
        try {
            $orders = Order::whereRaw('1=1')
                ->orderBy(
                    empty($request->query('sortBy')) ? 'id' : $request->query('sortBy'),
                    !empty($request->query('desc')) ? 'desc' : 'asc'
                );
            
            if (!empty($request->query('page'))) {
                $orders->skip($request->query('page'));
            }
            
            if (!empty($request->query('limit'))) {
                $orders->take($request->query('limit'));
            }
            return response()->json($orders->get(), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function shipmentLocator() {}

    function dashboard() {}

    function create(Request $request) {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            $payload['user_id'] = User::where('uuid', $request->input('user_uuid'))->value('id');
            $payload['order_status_id'] = OrderStatus::where('uuid', $payload['order_status_uuid'])->value('id');
            $payload['payment_id'] = Payment::where('uuid', $payload['payment_uuid'])->value('id');
            Order::create(Arr::only(
                $payload,
                [
                    'uuid',
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
            return response(null, 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response($th->getMessage(), 500);
        }
    }
    
    function show(Request $request, $uuid) {
        try {
            $order = Order::where('uuid', $uuid)->first();
            if (empty($order)) {
                return response(null, 404);
            }
            return response()->json($order, 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
    
    function update($uuid, Request $request) {
        try {
            $order = Order::where('uuid', $uuid);
            if ($order->count() === 0){
                return response(null, 404);
            }

            $payload = $request->post();
            $payload['user_id'] = User::where('uuid', $request->input('user_uuid'))->value('id');
            $payload['order_status_id'] = OrderStatus::where('uuid', $payload['order_status_uuid'])->value('id');
            $payload['payment_id'] = !empty($payload['payment_uuid']) ? 
                Payment::where('uuid', $payload['payment_uuid'])->value('id') : null;
            $order
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
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
    
    function delete($uuid) {
        try {
            $order = Order::where('uuid', $uuid);
            if ($order->count() === 0){
                return response(null, 404);
            }
            $order->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function download() {}
}
