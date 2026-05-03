<?php

namespace App\Http\Controllers;

use App\Services\CardService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'board_id' => 'required|exists:boards,id',
            'status' => 'required|string'
        ]);

        $card = $this->cardService::createCard($data);

        return response()->json($card, 201);
    }
}