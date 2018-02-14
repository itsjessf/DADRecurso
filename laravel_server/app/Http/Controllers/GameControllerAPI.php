<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Game as GameResource;
use App\Http\Resources\Card as CardResource;
use App\Http\Resources\Deck as DeckResource;
use App\Game;
use App\Deck;
use App\GameUser;
use App\User;
use Illuminate\Support\Facades\DB;

class GameControllerAPI extends Controller

{
    public function index()
    {
        return GameResource::collection(Game::all());
    }

    public function lobby()
    {
        return GameResource::collection(Game::where('status', 'pending')->get());
    }

    public function gamesStatus($status)
    {
        return GameResource::collection(Game::where('status', $status)->get());
    }

    public function getGame($id)
    {
        return new GameResource(Game::find($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'created_by' => 'required',
        ]);
        $player = User::findOrFail($request->input('created_by'));
        //Criar o jogo
        $game = new Game();
        $game->status = 'pending';   
        $game->created_by = $request->created_by;        
        $decks = Deck::where('active', 1);
        $deck = $decks->inRandomOrder()->first();
        $game->deck_used = $deck->id;
        $game->save();
        $game->addPlayer($request->created_by);

        return response()->json(new GameResource($game) ,201);
    }

    public function join(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        $gameUser = new GameUser();
        $gameUser->game_id = $game->id;
        $gameUser->user_id = $request->user_id;
        $gameUser->team_number = 0;
        $gameUser->save();
        return response()->json($gameUser, 200);
    }

    public function start(Request $request, $id){
        $game = Game::findOrFail($id);
        $game->status = 'active';
        $playerTeams =$request->playerTeams;

        for( $i = 0; $i< count($playerTeams) ; $i++){
            DB::table('game_user')
            ->where('game_id', $game->id)
            ->where('user_id', $playerTeams[$i]['id'])
            ->update(['team_number' => $playerTeams[$i]['team']]);
        }
        $cards = $game->cards;       
        $game->save();

        return response()->json(CardResource::collection($cards), 200);
    }



    public function endGame(Request $request, $id)
    {
        $game = Game::findOrFail($id); 
        foreach($game->players as $player){
            $gameUser = GameUser::where('game_id', '=', $game->id)->where('user_id', '=', $player->id)->first();
            if($gameUser->team_number == 1){
                $player->total_points += $request->team1_points;
            } else {
                $player->total_points += $request->team2_points;
            }

            $player->total_games_played ++;
            $player->save();
            
        }
        $game->status = 'terminated';
        $game->team1_cardpoints = $request->team1_cardpoints;
        $game->team2_cardpoints = $request->team2_cardpoints;
        $game->team_winner = $request->team_winner;
        $game->team_desconfiou = $request->team_desconfiou;
        $game->team_renunciou = $request->team_renunciou;
        $game->team1_points = $request->team1_points;
        $game->team2_points = $request->team2_points;
        $game->save();

        return new GameResource($game);
    }
}
