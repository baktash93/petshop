<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Payment;

class PaymentController extends Controller {
    function index() {
        $payments = Payment::where('1=1')
            ->skip($request->query('page'))
            ->take($request->query('limit'))
            ->orderBy($request->query('sortBy'), !empty($request->query('desc')))
            ->get();
        return response()->json($payments, 200);
    }

    function create(Request $request) {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid();
            Payment::create(Arr::only($payload, [
                    'type',
                    'details',
                    'uuid'
                ])
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    function show($uuid) {
        return Payment::where('uuid', $uuid)->first();
    }

    function update($uuid, Request $request) {
        try {
            Payment::where('uuid', $uuid)
                ->update(Arr::only($request->post(), [
                    'type',
                    'details'
                ]));
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function delete($uuid, Request $request) {
        try {
            Payment::where('uuid', $uuid)
                ->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
}
