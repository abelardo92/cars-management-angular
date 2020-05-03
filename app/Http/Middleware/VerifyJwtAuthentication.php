<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\JwtAuth;

class VerifyJwtAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            return $next($request);
        }
        abort(403, "Not authorized");
    }
}
