<?php

namespace App\Repositories\Eloquent;

use App\Models\Card;
use App\Repositories\Contracts\CardRepositoryInterface;

class EloquentCardRepository implements CardRepositoryInterface
{
    public function getAll()
    {
        return Card::all();
    }

    public function findById($id)
    {
        return Card::findOrFail($id);
    }

    public function create(array $data)
    {
        return Card::create($data);
    }

    public function update($id, array $data)
    {
        $card = $this->findById($id);
        $card->update($data);
        return $card;
    }

    public function delete($id)
    {
        $card = $this->findById($id);
        return $card->delete();
    }
}