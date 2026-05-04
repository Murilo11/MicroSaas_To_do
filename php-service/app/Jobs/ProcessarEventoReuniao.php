<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use App\Models\Card;

class ProcessarEventoReuniao extends RabbitMQJob
{
    /**
     * Execute the job.
     */
    public function fire(): void
    {
        $this->handle();
    }

    public function handle(): void
    {
        $body = $this->getRawBody();
        $dados = json_decode($body, true);

        if (is_array($dados) && isset($dados['title'])) {
            $titulo = $dados['title'];
            $descricaoBase = $dados['description'] ?? '';
            $dataReuniao = $dados['date'] ?? '';
            
            $conteudo = trim($descricaoBase . "\n" . $dataReuniao);

            // Associar a um board_id e column_id existentes (assumindo 1 por padrão)
            $cardData = [
                'title' => $titulo,
                'description' => $conteudo,
                'board_id' => 1,
            ];

            // If column_id exists in fillable, let's add it too.
            if (in_array('column_id', (new Card())->getFillable())) {
                $cardData['column_id'] = 1;
            }

            Card::create($cardData);
        }

        $this->delete();
    }
}
