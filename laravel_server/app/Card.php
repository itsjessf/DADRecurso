<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Deck;

class Card extends Model
{
    protected $table = "cards";

    protected $fillable = [
        'value',
        'suite',
        'deck_id',
        'path',
    ];

    public function deck()
    {
        return $this->hasOne('App\Deck', 'id', 'deck_id');
    }
}
