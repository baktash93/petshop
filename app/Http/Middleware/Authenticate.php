<?php

namespace App\Http\Middleware;
use Closure;
use App\Interfaces\IAuthTokenService;
use App\Interfaces\ITokenStoreService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    private $authService;
    private $tokenStore;

    public function __construct(IAuthTokenService $auth, ITokenStoreService $tokenStore) {
        $this->authService = $auth;
        $this->tokenStore = $tokenStore;
    }

    public function handle($request, Closure $next, ...$guards) {
        if (empty($request->header('authorization'))
            || !$this->authService->verify(explode(' ', $request->header('authorization'))[1])) {
            return response(null, 401);
        }
        $token = explode(' ', $request->header('authorization'))[1];
        $input = $request->all();
        $input['user_uuid'] = $this->authService->parse($token)->claims()->get('user_uuid');
        $request->replace($input);

        $userId = User::where('uuid', $input['user_uuid'])->value('id');
        if (!empty($tokenItem = $this->tokenStore->getItem($userId))) {
            if ($tokenItem->expires_at <= Carbon::now()) {
                return response(null, 401);
            }
        }
        return $next($request);
    }

}
