<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $this->channel = $this->connection->channel();
    }

    public function publish(string $queue, array $message)
    {
        $serviceName = 'rabbitmq';

        $ganesha = app(CircuitBreakerService::class)->getGanesha();

        // Circuito aberto
        if (!$ganesha->isAvailable($serviceName)) {

            Log::warning('Circuit Breaker ABERTO para RabbitMQ');

            return [
                'success' => false,
                'fallback' => true,
                'message' => 'RabbitMQ temporariamente indisponível'
            ];
        }

        try {

            retry(3, function () use ($queue, $message) {

                $this->channel->queue_declare(
                    $queue,
                    false,
                    true,
                    false,
                    false
                );

                $msg = new AMQPMessage(
                    json_encode($message),
                    ['delivery_mode' => 2]
                );

                $this->channel->basic_publish(
                    $msg,
                    '',
                    $queue
                );

            }, 200);

            // Sucesso
            $ganesha->success($serviceName);

            Log::info('Mensagem enviada com sucesso', [
                'queue' => $queue
            ]);

            return [
                'success' => true
            ];

        } catch (\Throwable $e) {

            // Falha registrada no circuit breaker
            $ganesha->failure($serviceName);

            Log::error('Erro RabbitMQ', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'fallback' => true,
                'error' => $e->getMessage()
            ];
        }
    }


    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }

        if ($this->connection) {
            $this->connection->close();
        }
    }
}