<?php

namespace App\Http\Middleware;
use Closure;
use App\Interfaces\IAuthTokenService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    private $authService;

    public function __construct(IAuthTokenService $auth) {
        $this->authService = $auth;
        // \Log::debug([$this->authService]);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards) {
        if (!$this->authService->verify(explode(' ', $request->header('authorization'))[1])) {
            return response('', 401);
        }
        return $next($request);
    }
}
