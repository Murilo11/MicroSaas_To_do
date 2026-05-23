<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtém do cabeçalho da requisição ou gera um novo UUID
        $correlationId = $request->header('X-Correlation-ID') ?: (string) Str::uuid();

        // 2. Garante que o Correlation ID está nos cabeçalhos da Request
        $request->headers->set('X-Correlation-ID', $correlationId);

        // 3. Compartilha no contexto de logs global da aplicação
        Log::shareContext([
            'correlation_id' => $correlationId,
        ]);

        // 4. Executa a requisição
        $response = $next($request);

        // 5. Retorna o Correlation ID no cabeçalho da Response para rastreabilidade externa
        $response->headers->set('X-Correlation-ID', $correlationId);

        return $response;
    }
}
