<?php

namespace App\Services;

use App\Models\Card; 
use Exception;

class CardService
{
    protected $rabbitMQ;

    public function __construct(RabbitMQService $rabbitMQ)
    {
        $this->rabbitMQ = $rabbitMQ;
    }

    public function createCard(array $data)
    {
        $card = Card::create($data);

        $this->rabbitMQ->publish('cards_queue', [
            'event' => 'card_created',
            'card_id' => $card->id,
            'title' => $card->title
        ]);

        return $card;
    }

    public function moveCard($id, $newStatus)
    {
        $card = Card::findOrFail($id);
        $card->status = $newStatus;
        $card->save();

        $this->rabbitMQ->publish('cards_queue', [
            'event' => 'card_moved',
            'card_id' => $card->id,
            'new_status' => $newStatus
        ]);

        return $card;
    }
}