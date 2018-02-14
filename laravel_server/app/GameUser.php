<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class GameUser extends Model
{
    public $timestamps = false;
    protected $table = "game_user";
    //protected $primaryKey = 'game_id';

    protected $fillable = [
        'game_id',
        'user_id',
        'team_number',
    ];
    public function game(){
        return $this->belongsTo('App\Game');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
