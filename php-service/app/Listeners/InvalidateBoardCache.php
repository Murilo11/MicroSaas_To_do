<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class InvalidateBoardCache
{
    public function handle($event)
    {
        // O $event conterá os dados vindos do RabbitMQ (ex: id do board)
        $boardId = $event['board_id'] ?? null;

        if ($boardId) {
            // Remove o item específico do cache [cite: 28, 40]
            Cache::forget("board:item:{$boardId}");
        }

        // Também limpa a listagem geral para garantir consistência
        Cache::forget('boards:all');
    }
}