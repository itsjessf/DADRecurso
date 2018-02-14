<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Card as CardResource;
use App\Card;
use App\Deck;

class CardControllerAPI extends Controller
{
    public function delete($id)
    {
        $card = Card::findOrFail($id);
        $deck = $card->deck;
        $deck->complete = 0;
        $deck->active = 0;
        $deck->save();
        $card->delete();
        return response()->json("Card has been deleted", 204);
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required',
            'suite' => 'required',
            'path' => 'required',
            'deck_id' => 'required',
        ]);

        $card = new Card();
        $card->value = $request->value;
        $card->suite = $request->suite;
        $card->deck_id = $request->deck_id;
        $card->path = $request->path;
        $card->save();
        return response()->json(new CardResource($card), 201);
    }
}
