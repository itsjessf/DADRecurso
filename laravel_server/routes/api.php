<?php

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = Auth::user();
    $user->average = $user->average();
    return $user;
});
Route::middleware('auth:api', 'admin')->get('users', 'UserControllerAPI@getUsers');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::middleware('auth:api', 'admin')->get('users/{id}', 'UserControllerAPI@getUser');
Route::post('users', 'UserControllerAPI@store');
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api', 'admin')->delete('users/{id}', 'UserControllerAPI@delete');
Route::middleware('auth:api', 'admin')->post('users/{id}/block', 'UserControllerAPI@toggleBlockUser');
Route::middleware('auth:api', 'admin')->put('users/{id}/storeReasonBlock', 'UserControllerAPI@storeReasonBlock');
Route::middleware('auth:api', 'admin')->middleware('auth:api')->put('users/admin/{id}', 'UserControllerAPI@updateAdmin');

Route::get('games', 'GameControllerAPI@index');
Route::get('games/lobby', 'GameControllerAPI@lobby');
Route::get('games/status/{status}', 'GameControllerAPI@gamesStatus');
Route::get('games/{id}', 'GameControllerAPI@getGame');
Route::post('games', 'GameControllerAPI@store');
Route::patch('games/{id}/join', 'GameControllerAPI@join');
Route::patch('games/{id}/start', 'GameControllerAPI@start');
Route::patch('games/{id}/endGame', 'GameControllerAPI@endGame');
//Route::patch('games/{id}/endgame/{winner}', 'GameControllerAPI@endgame');

Route::middleware('auth:api', 'admin')->get('decks', 'DeckControllerAPI@getDecks');
Route::middleware('auth:api', 'admin')->get('decks/{id}/cardBack', 'DeckControllerAPI@getCardBack');
Route::middleware('auth:api', 'admin')->get('decks/{id}/cards', 'DeckControllerAPI@getCards');
Route::middleware('auth:api', 'admin')->get('decks/{id}', 'DeckControllerAPI@getDeck');
Route::middleware('auth:api', 'admin')->post('decks/{id}/activate', 'DeckControllerAPI@toggleActivateDeck');
Route::middleware('auth:api', 'admin')->post('decks', 'DeckControllerAPI@store');
Route::middleware('auth:api', 'admin')->delete('decks/{id}', 'DeckControllerAPI@delete');
Route::middleware('auth:api', 'admin')->get('decks/{id}/missingCards', 'DeckControllerAPI@getMissingCards');
Route::middleware('auth:api', 'admin')->patch('decks/{id}/complete', 'DeckControllerAPI@completeDeck');

Route::post('upload', 'ImageControllerAPI@store');
Route::get('images/cardBackPaths', 'ImageControllerAPI@getCardBackPaths');
Route::middleware('auth:api', 'admin')->delete('cards/{id}', 'CardControllerAPI@delete');
Route::middleware('auth:api', 'admin')->post('cards/store', 'CardControllerAPI@store');

Route::middleware('auth:api', 'admin')->get('config/getPlatformEmail', 'ConfigControllerAPI@getPlatformEmail');
Route::middleware('auth:api', 'admin')->put('config/updatePlatformEmail', 'ConfigControllerAPI@updatePlatformEmail');

Route::get('statistics/totalGames', 'StatisticsControllerAPI@getTotalGames');
Route::get('statistics/totalUsers', 'StatisticsControllerAPI@getTotalUsers');
Route::middleware('auth:api')->get('statistics/player/{id}/totalGames', 'StatisticsControllerAPI@getPlayerTotalGames');
Route::middleware('auth:api')->get('statistics/player/{id}/totalScores', 'StatisticsControllerAPI@getPlayerTotalScores');
Route::middleware('auth:api')->get('statistics/player/{id}/averageScores', 'StatisticsControllerAPI@getPlayerAverageScores');
Route::middleware('auth:api')->get('statistics/player/{id}/totalWins', 'StatisticsControllerAPI@getPlayerTotalWins');
Route::middleware('auth:api')->get('statistics/player/{id}/totalDraws', 'StatisticsControllerAPI@getPlayerTotalDraws');
Route::middleware('auth:api')->get('statistics/player/{id}/totalDefeats', 'StatisticsControllerAPI@getPlayerTotalDefeats');
Route::get('statistics/top/totalGames/{number}', 'StatisticsControllerAPI@getTopTotalGames');
Route::get('statistics/top/points/{number}', 'StatisticsControllerAPI@getTopPoints');
Route::get('statistics/top/average/{number}', 'StatisticsControllerAPI@getTopAverage');
Route::middleware('auth:api', 'admin')->get('statistics/player/list', 'StatisticsControllerAPI@getListPlayers');
Route::middleware('auth:api', 'admin')->get('statistics/gamesByDay', 'StatisticsControllerAPI@getCountOfGamesDaily');



Route::post('loginUser', 'LoginControllerAPI@loginUser');
Route::post('loginAdmin', 'LoginControllerAPI@loginAdmin');
Route::post('register', 'UserControllerAPI@store');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

//Mail
Route::get('/{token}', 'LoginControllerAPI@confirmation')->name('account.confirmation');
