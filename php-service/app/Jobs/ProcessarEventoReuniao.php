<?php

namespace App\Jobs;

// Importa a classe do pacote que o seu 'find' localizou
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use App\Repositories\Contracts\CardRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProcessarEventoReuniao extends RabbitMQJob
{
    public function handle(CardRepositoryInterface $cardRepository): void
    {
        try {
            $body = $this->getRawBody(); //[cite: 85]
            $dados = json_decode($body, true); //[cite: 85]

            Log::info('RabbitMQ: Recebendo evento de reunião', ['payload' => $dados]); //[cite: 113, 133]

            if (is_array($dados) && isset($dados['title'])) {
                $cardData = [
                    'title'       => $dados['title'],
                    'description' => $dados['description'] ?? 'Criado via Node.js',
                    'board_id'    => 1, // Valor fixo inicial como no seu original
                ];

                $cardRepository->create($cardData); //[cite: 119]
                
                Log::info('RabbitMQ: Card criado com sucesso!'); // [cite: 120]
            }

            $this->delete(); 

        } catch (\Exception $e) {
            Log::error('RabbitMQ: Erro ao processar job: ' . $e->getMessage()); // [cite: 122]
            throw $e; //  [cite: 123]
        }
    }
}