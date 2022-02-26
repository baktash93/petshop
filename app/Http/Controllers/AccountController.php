<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AccountController extends Controller {
    function login(Request $request) {
        try {
            if(User::where('email', $request->post('email'))->count() === 0) {
                return response('', 404);
            }
    
            $cfg = Configuration::forSymmetricSigner(
            // You may use any HMAC variations (256, 384, and 512)
                new Sha256(),
                // replace the value below with a key of your own!
                InMemory::base64Encoded('aGVsbG8=')
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );
            $token = $cfg
            ->builder()
            ->issuedBy(config('values.APP_URL'))
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            ->getToken($cfg->signer(), $cfg->signingKey());
            \Log::info($token->toString());

            return response($token->toString(), 200);
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
