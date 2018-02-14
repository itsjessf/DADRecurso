<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Deck as DeckResource;
use App\Card;

class Deck extends Model
{
    protected $table = "decks";

    protected $fillable = [
        'name',
        'hidden_face_image_path',
        'active',
        'complete',
    ];

    protected $suites = [
        'Club' => 'p',
        'Diamond' => 'o',
        'Heart'=> 'c',
        'Spade' => 'e',
    ];

    protected $values = [
        'Ace' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        'Jack'  => 11,
        'Queen'  => 12,
        'King'  => 13,
    ];

    public static function boot()
    {
        parent::boot();    
        static::deleting(function($deck) {
            $deck->games()->delete();
            $deck->cards()->delete();
        });
        
    }   

    public function games()
    {
        return $this->hasMany('App\Game','deck_used', 'id');
    }
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    
    public function fillCards()
    {
        foreach ($this->values as $value=>$pathValue) {
            foreach ($this->suites as $suite=>$pathSuite) {
                $card = new Card();
                $card->value = $value;
                $card->suite = $suite;
                $card->deck_id = $this->id;
                $card->path = "img/".$pathSuite.$pathValue.".png";
                $card->save();
            }
        }       
        return response()->json(new DeckResource($card), 201);
    }
}
