<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Interfaces\IAuthTokenService;

class AccountController extends Controller {
    function login(Request $request, IAuthTokenService $auth) {
        try {
            if(User::where('email', $request->post('email'))->count() === 0) {
                return response('', 404);
            }
    
            $user = User::where('email', $request->post('email'))->first();
            $auth->sign('user_uuid', $user->uuid, '+ 1hour');
            
            return response($auth->getToken(), 200);
        } catch (\Throwable $th) {
            throw $th;
            response('', 500);
        }
    }

    function logout() {}

    function create(Request $request) {
        try {
            if ($request->post('password') !== $request->post('confirm_password')) {
                return response('', 422);
            }
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid();
            $payload['password'] = md5($request->post('password'));
            User::create(Arr::only($payload, [
                    'uuid',
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'avatar',
                    'address',
                    'phone_number',
                    'is_marketing'
                ])
            );
            return response('', 200);
        } catch (\Throwable $th) {
            return response('', 500);
        }
    }

    function forgotPassword() {}

    function resetPasswordToken() {}
}
