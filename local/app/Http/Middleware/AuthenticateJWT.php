<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateJWT
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'ไม่สามารถตรวจสอบได้ กรุณา login ใหม่',
                'status' => 0,
                'code' => 'ER04',
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}
