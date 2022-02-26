<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller {
    function login() {}

    function logout() {}

    function create(Request $request) {
        try {
            if ($request->post('password') !== $request->post('confirm_password')) {
                return response('', 422);
            }
            User::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'email' => $request->post('email'),
                'password' => md5($request->post('password')),
                'avatar' => $request->post('avatar'),
                'address' => $request->post('address'),
                'phone_number' => $request->post('phone_number'),
                'is_marketing' => $request->post('is_marketing')
            ]);
            return response('', 200);
        } catch (\Exception $e) {
            return response('', 500);
        }
    }

    function forgotPassword() {}

    function resetPasswordToken() {}
}
