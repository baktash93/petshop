<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\OrderStatus;

class OrderStatusController extends Controller {
    function index(Request $request) {
        try {
            $statuses = OrderStatus::whereRaw('1=1')
                ->orderBy(
                    empty($request->query('sortBy')) ? 'id' : $request->query('sortBy'),
                    !empty($request->query('desc')) ? 'desc' : 'asc'
                );
            
            if (!empty($request->query('page'))) {
                $statuses->skip($request->query('page'));
            }
            
            if (!empty($request->query('limit'))) {
                $statuses->take($request->query('limit'));
            }
            return response()->json($statuses->get(), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function create(Request $request) {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid()->toString();
                OrderStatus::create(Arr::only($payload, [
                    'uuid',
                    'title'
                ])
            );
            return response('', 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    function show(Request $request, $uuid) {
        try {
            $status = OrderStatus::where('uuid', $uuid)->first();
            if (empty($status)) {
                return response(null, 404);
            }
            return response()->json($status, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
    
    function update(Request $request, $uuid) {
        try {
            $status = OrderStatus::where('uuid', $uuid);
            if ($status->count() === 0){
                return response(null, 404);
            }
            $status
                ->update(Arr::only($request->post(), [
                    'title'
                ]));
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
    
    function delete($uuid, Request $request) {
        try {
            $status = OrderStatus::where('uuid', $uuid);
            if ($status->count() === 0){
                return response(null, 404);
            }
            OrderStatus::where('uuid', $uuid)
                ->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
}
