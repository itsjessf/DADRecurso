<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Deck as DeckResource;
use App\Http\Resources\Card as CardResource;
use App\Deck;
use App\Card;

class DeckControllerAPI extends Controller
{
    public function getDecks(Request $request)
    {
        if ($request->has('page')) {
            return DeckResource::collection(Deck::paginate(5));
        } else {
            return DeckResource::collection(Deck::all());
        }
    }

    public function getDeck($id)
    {
        $deck = Deck::findOrFail($id);
        return new DeckResource($deck);
    }

    public function getCards($id)
    {
        $deck = Deck::findOrFail($id);
        $cards = $deck->cards;
        return response()->json(CardResource::collection($cards), 200);
    }

    public function delete($id)
    {
        $deck = Deck::findOrFail($id);
        $cards = $deck->cards;
        Card::destroy($cards);
        $deck->delete();
        return response()->json(null, 204);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'hidden_face_image_path' => 'required',
        ]);
        //Criar o deck
        $deck = new Deck();
        $deck->name = $request->name;   
        $deck->hidden_face_image_path = $request->hidden_face_image_path;        
        $deck->active = 1;
        $deck->complete = 1;      
        $deck->save();
        $deck->fillCards();

        return response()->json(new DeckResource($deck) ,201);
    }

    public function getCardBack(Request $request,$id)
    {
        $deck = Deck::findOrFail($id);
        return $deck->hidden_face_image_path;
    }

    public function getMissingCards(Request $request,$id)
    {
        $deck = Deck::findOrFail($id);
        $cards = $deck->cards;
        $missingCards = [];
        $values = [
            'Ace' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            'Jack' => 11,
            'Queen' =>12,
            'King' => 13,
        ];
        $suites = [
            'Club' => 'p',
            'Diamond' =>'o',
            'Heart' => 'c',
            'Spade' => 'e',
        ];
        foreach($values as $value => $pathValue){
            foreach($suites as $suite => $pathSuite){
                if(!($cards->where('value', $value)->where('suite',$suite)->first())){
                    $card = [$value,$suite,$pathSuite.$pathValue]; 
                    array_push($missingCards, $card);
                }
            }
        }

        return response()->json($missingCards, 200);
    }

    public function toggleActivateDeck(Request $request, $id)
    {
        $deck = Deck::findOrFail($id);
        //Só se pode ativar um deck quando está completo
        if($deck->complete == 1){
            if($deck->active == 1 ){
                $deck->active = 0;
                $deck->save();
                return response()->json(new DeckResource($deck), 201);
            }
            $deck->active = 1;
            $deck->save();
            return response()->json(new DeckResource($deck), 201);
        }

    }

    public function completeDeck(Request $request, $id)
    {
        $deck = Deck::findOrFail($id);
        $deck->complete = 1;
        $deck->save();
        return response()->json(new DeckResource($deck), 201);
    }
}
