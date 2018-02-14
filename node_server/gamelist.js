/*jshint esversion: 6 */

var MemoryGame = require('./gamemodel.js');

class GameList {
	constructor() {
        this.games = new Map();
    }

    gameByID(gameID) {
    	let game = this.games.get(gameID);
    	return game;
    }

    createGame(createdGame, user) {
    	var game = new MemoryGame(createdGame, user);
    	this.games.set(game.gameID, game);
    	return game;
    }

    joinGame(gameID,user) {
    	let game = this.gameByID(gameID);
    	if (game===null) {
    		return null;
    	}
        game.join(user);
        return game;
    }

    startGame(gameID, cards) {

        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        game.startGame(cards);
        return game;
    }

    questionPlay(gameID,userID) {

        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        let player = game.getPlayer(userID);
        game.questionPlay(player);
        return game;
    }
    stand(gameID, userID) {
        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        let player = game.getPlayer(userID);
        game.stand(player);
        return game;
    }

    checkGameEnded(gameID) {
        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }       
        return game.checkGameEnded();
    }

    closeGame(game) {
    	if (game===null) {
    		return null;
    	}
        let status = this.games.delete(game.gameID, game);;
        return status;
    }

    leaveGame(gameID, userID) {
        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        console.log("Leaving in gamelist");
        game.leave(userID);
        game.checkGameEnded();
        if(game.players.length == 0){
            if(this.closeGame(game)){
                console.log("game closed");
            }else{
                console.log("Couldn't close game");
            }
        } 
        return game;
    }

    getConnectedGamesOf(userID) {
       let games = [];
       for (var [key, value] of this.games) {
        for(let i=0; i< value.players.length; i++){
            if (value.players[i].id == userID) {
                games.push(value);
            }
        }

    }
    return games;
}

getLobbyGamesOf(userID) {
    let games = [];
    for (var [key, value] of this.games) {
        if (!value.gameStarted && !value.gameEnded && !value.hasPlayer(userID))  {
            games.push(value);
        }
    }
    return games;
}

}

module.exports = GameList;
