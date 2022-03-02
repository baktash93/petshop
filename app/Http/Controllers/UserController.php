<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class UserController extends Controller {
    function show(Request $request) {
        try {
            $user = User::where('uuid', $request->input('user_uuid'))->first();
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function delete(Request $request) {
        try {
            if (User::where('uuid', $request->input('user_uuid'))->count() === 0) {
                return response(null, 404);
            }
            User::where('uuid', $request->input('user_uuid'))->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function edit(Request $request) {
        try {
            $payload = $request->post();
            User::where('uuid', $request->input('user_uuid'))
                ->update(Arr::only($payload, [
                    'first_name',
                    'last_name',
                    'is_marketing',
                    'phone_number'
                ]));
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }
}
