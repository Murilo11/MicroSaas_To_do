<?php

namespace App\Repositories\Eloquent;

use App\Models\Board;
use App\Repositories\Contracts\BoardRepositoryInterface;

class EloquentBoardRepository implements BoardRepositoryInterface
{
    public function getAll()
    {
        return Board::all();
    }

    public function findById($id)
    {
        return Board::findOrFail($id);
    }

    public function create(array $data)
    {
        return Board::create($data);
    }

    public function update($id, array $data)
    {
        $board = $this->findById($id);
        $board->update($data);
        return $board;
    }

    public function delete($id)
    {
        $board = $this->findById($id);
        return $board->delete();
    }
}
