<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['board_id', 'title', 'description', 'position'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
