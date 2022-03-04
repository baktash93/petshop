<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller 
{
    function index(Request $request)
    {
        try {
            $payments = Payment::whereHas('order', function ($query) use($request) {
                return $query->where('user_id', User::where('uuid', $request->input('user_uuid'))->value('id'));
            })->orderBy(
                empty($request->query('sortBy')) ? 'id' : $request->query('sortBy'),
                !empty($request->query('desc')) && $request->query('desc') == 'true' ? 'desc' : 'asc'
            );
            
            if (!empty($request->query('page'))) {
                $payments->skip($request->query('page'));
            }
            
            if (!empty($request->query('limit'))) {
                $payments->take($request->query('limit'));
            }
            return response()->json($payments->get(), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function create(Request $request)
    {
        try {
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            Payment::create(Arr::only($payload, [
                    'type',
                    'details',
                    'uuid'
                ])
            );
            return response('', 201);
        } catch (\Throwable $th) {
            // throw $th;
            return response(null, 500);
        }
    }
    
    function show($uuid)
    {
        try {
            $payment = Payment::where('uuid', $uuid)->first();
            if (empty($payment)) {
                return response(null, 404);
            }
            return response()->json($payment, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function update($uuid, Request $request)
    {
        try {
            $payment = Payment::where('uuid', $uuid);
            if ($payment->count() === 0){
                return response(null, 404);
            }
            $payment
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

    function delete($uuid, Request $request)
    {
        try {
            $payment = Payment::where('uuid', $uuid);
            if ($payment->count() === 0){
                return response(null, 404);
            }
            $payment->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
}
