<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token não fornecido ou inválido'], 401);
        }

        try {

            $token = str_replace('Bearer ', '', $authHeader);

            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $request->attributes->add(['user_data' => $decoded]);

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido ou expirado'], 401);
        }
    }
}