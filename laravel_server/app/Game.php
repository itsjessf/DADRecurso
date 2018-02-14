<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Game as GameResource;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'total_players',
        'created_by',
        'deck_used',
    ];

    public function cards()
    {
        return $this->hasMany('App\Card', 'deck_id', 'deck_used');
    }
    public function players()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\GameUser',
            'game_id', 
            'id', 
            'id', 
            'user_id'
        );    
    }

    public static function boot()
    {
        parent::boot();    
        static::deleting(function($game) {
            $game->game_user()->delete();
        });
        
    }   
    public function games()
    {
        return $this->hasMany('App\GameUser','game_id', 'id');
    }



    public function addPlayer($user_id)
    {
        $gameUser = new GameUser();
        $gameUser->game_id = $this->id;
        $gameUser->user_id = $user_id;
        $gameUser->team_number = 0;
        $gameUser->save();
        return response()->json(new GameResource($gameUser), 201);
    }

}
