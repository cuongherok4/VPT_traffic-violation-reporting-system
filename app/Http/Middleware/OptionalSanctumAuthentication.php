<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuthentication
{
    public function __construct(private readonly AuthFactory $auth) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = $this->auth->guard('sanctum')->user();

            if ($user) {
                $request->setUserResolver(fn () => $user);
            }
        } catch (AuthenticationException) {
            //
        }

        return $next($request);
    }
}
