<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Support\Facades\DB;
use App\Game;
use App\GameUser;
use App\User;

class StatisticsControllerAPI extends Controller
{

    //GLOBAL
    public function getTopTotalGames(Request $request, $number)
    {       
        $allPlayers = User::select('id', 'nickname', 'total_games_played')->get();

        for($i = 0; $i < sizeOf($allPlayers); $i ++){
            for($x = 0; $x < sizeOf($allPlayers) - 1; $x ++){
                if($allPlayers[$x + 1]->total_games_played > $allPlayers[$x]->total_games_played){
                    $playerHolder = $allPlayers[$x + 1];
                    $allPlayers[$x + 1] = $allPlayers[$x];
                    $allPlayers[$x] = $playerHolder;
                }
            }
        }

        return response()->json(
            ['data'=> $allPlayers->slice(0,$number)], 200);

    }

    public function getListPlayers(Request $request)
    {       
        $allPlayers = User::select('id', 'nickname', 'total_games_played')->get();
        foreach($allPlayers as $player){
            $player->draws = $player->draws();
            $player->defeats = $player->defeats();
            $player->wins = $player->wins();

        }

        return response()->json(
            ['data'=> $allPlayers], 200);

    }

    public function getCountOfGamesDaily(Request $request)
    {   

        $games = DB::table('games')
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) count'))
        ->groupBy('date')
        ->get();
        return response()->json(
            ['data'=>$games], 200);
    }
    
    public function getTopPoints(Request $request, $number)
    {       
        $allPlayers = User::select('id', 'nickname', 'total_points')->get();
        for($i = 0; $i < sizeOf($allPlayers); $i ++){
            for($x = 0; $x < sizeOf($allPlayers) - 1; $x ++){
                if($allPlayers[$x + 1]->total_points > $allPlayers[$x]->total_points){
                    $playerHolder = $allPlayers[$x + 1];
                    $allPlayers[$x + 1] = $allPlayers[$x];
                    $allPlayers[$x] = $playerHolder;
                }
            }
        }

        return response()->json(
            ['data'=> $allPlayers->slice(0,$number)], 200);

    }
    public function getTopAverage(Request $request, $number)
    {       
        $allPlayers = User::select('id', 'nickname', 'total_points', 'total_games_played')->get();
        foreach($allPlayers as $player){
            $player->average = $player->average();

        }

        for($i = 0; $i < sizeOf($allPlayers); $i ++){
            for($x = 0; $x < sizeOf($allPlayers) - 1; $x ++){
                if($allPlayers[$x + 1]->average > $allPlayers[$x]->average){
                    $playerHolder = $allPlayers[$x + 1];
                    $allPlayers[$x + 1] = $allPlayers[$x];
                    $allPlayers[$x] = $playerHolder;
                }
            }
        }

        return response()->json(
            ['data'=> $allPlayers->slice(0,5)], 200);

    }

    public function getTotalGames(Request $request)
    {
        return DB::table('games')->count();
    }
    public function getTotalUsers(Request $request)
    {
        return DB::table('users')->count();
    }


    //USERS
    public function getPlayerTotalGames(Request $request, $id)
    {
        $player = User::findOrFail($request->id);
        return $player->games()->count();
    }
    public function getPlayerTotalScores(Request $request, $id)
    {
        $player = User::findOrFail($request->id);
        return $player->select('total_points')->get();
    }
    public function getPlayerAverageScores(Request $request, $id)
    {
        $player = User::findOrFail($request->id);      
        return getPlayerTotalScores($player)/getPlayerTotalWins($player);
    }
    public function getPlayerTotalWins(Request $request)
    {
        $player = User::findOrFail($request->id);
        return $player->wins();
    }
    public function getPlayerTotalDraws(Request $request)
    {
        $player = User::findOrFail($request->id);
        return $player->draws();
    }
    public function getPlayerTotalDefeats(Request $request)
    {
        $player = User::findOrFail($request->id);
        return $player->defeats();
    }



    
}
