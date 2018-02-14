<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'nickname',
        'admin',
        'blocked',
        'reason_blocked',
        'reason_reactivated',
        'total_points',
        'total_games_played',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    protected $hidden = [
        'password', 'password_confirmation','remember_token', 
    ];

    public function isAdmin(){
        return $this->admin == 1;
    }

    public function isBlocked(){
        return $this->blocked == 1;
    }


    public static function boot()
    {
        parent::boot();    
        static::deleting(function($user) {
            $user->game_user()->delete();
            $user->games()->delete();
        });
        
    }  
    public function wins()
    {
        $wins = 0;
        
        foreach($this->game_user as $game_user){
            if($game_user->game->status = 'terminated' && $game_user->game->team_winner == $game_user->team_number){
                $wins ++;
            }
        }
        return $wins;
    }

    public function average()
    {
        if($this->total_games_played <= 0){
            return 0;
        }
        return round($this->total_points / (float) $this->total_games_played, 2);
    }

    public function draws()
    {
        $draws = 0;
        foreach($this->game_user as $game_user){
            if($game_user->game->status = 'terminated' && $game_user->game->team_winner == 0){
                $draws ++;
            }
        }
        return $draws;
    }

    public function defeats()
    {
        $defeats = 0;
        foreach($this->game_user as $game_user){
            if($game_user->game->status = 'terminated'
                && $game_user->game->team_winner != 0 
                && $game_user->game->team_winner != $game_user->team_number)
            {
                $defeats ++;
            }
        }
        return $defeats;
    }


    public function games()
    {
        return $this->hasManyThrough(
            'App\Game',
            'App\GameUser',
            'user_id', 
            'id', 
            'id', 
            'game_id'
        );
    }

    public function game_user()
    {
        return $this->hasMany('App\GameUser', 'user_id', 'id');
    }
}