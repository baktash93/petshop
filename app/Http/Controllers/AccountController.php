<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Interfaces\IAuthTokenService;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class AccountController extends Controller {
    function login(Request $request, IAuthTokenService $auth) {
        try {
            if(User::where('email', $request->post('email'))->count() === 0) {
                return response('', 404);
            }
            $user = User::where('email', $request->post('email'))->first();
            if (\Hash::check($request->post('password'), $user->password)){
                $auth->sign('user_uuid', $user->uuid, config('values.SESSION_MAX_AGE'));
                User::where('email', $request->post('email'))
                    ->update(['last_login_at' => Carbon::now()]);
                return response($auth->getToken(), 200);
            }
            return response(null, 401);
        } catch (\Throwable $th) {
            throw $th;
            response(null, 500);
        }
    }

    function logout() {}

    function create(Request $request) {
        try {
            if ($request->post('password') !== $request->post('confirm_password')) {
                return response(null, 422);
            }
            if (User::where('email', $request->post('email'))->count() > 0) {
                return response(null, 409);
            }
            $payload = $request->post();
            $payload['uuid'] = \Illuminate\Support\Str::uuid();
            $payload['password'] = bcrypt($request->post('password'));
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
            return response(null, 201);
        } catch (\Throwable $th) {
            return response(null, 500);
        }
    }

    function forgotPassword() {}

    function resetPasswordToken() {}
}
