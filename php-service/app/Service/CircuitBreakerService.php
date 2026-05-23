<?php

namespace App\Service;

use Ackintosh\Ganesha\Builder;
use Ackintosh\Ganesha\Ganesha;
use Ackintosh\Ganesha\Storage\Adapter\Redis as RedisAdapter;
use Illuminate\Support\Facades\Redis;

class CircuitBreakerService
{
    protected Ganesha $ganesha;

    public function __construct()
    {
        // Obtém o cliente Redis configurado no Laravel
        $redisClient = Redis::connection()->client();

        // Configura o Circuit Breaker usando a estratégia de Taxa de Falhas
        $this->ganesha = Builder::withRateStrategy()
            ->adapter(new RedisAdapter($redisClient))
            ->failureRateThreshold(50)    // Abre o circuito se 50% ou mais das requisições falharem
            ->timeWindow(30)              // Janela de análise de 30 segundos
            ->minimumRequests(5)          // Requisições mínimas necessárias antes de começar a validar a taxa
            ->intervalToHalfOpen(15)      // Permanece aberto por 15 segundos antes de passar para meio-aberto
            ->build();
    }

    public function getGanesha(): Ganesha
    {
        return $this->ganesha;
    }
}
