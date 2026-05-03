<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['title'];
    
    public function cards()
    {
        return $this->hasMany(Card::class);
        
    }
}
