/*jshint esversion: 6 */


var app = require('http').createServer();
const axios = require("axios");

var io = require('socket.io')(app);
var MemoryGame = require('./gamemodel.js');
var api_url = "http://projeto.dad";
var GameList = require('./gamelist.js');

// ------------------------
// Estrutura dados - server
// ------------------------
let games = new GameList();

app.listen(8080, function(){
    console.log('listening on *:8080');
});

io.on('connection', function (socket) {
    console.log('client has connected');

    socket.on('authentication', function (token){
        let config = {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        };
        axios.get(api_url+'/api/user', config).then(response =>{
            this.user = response.data;
            socket.emit('authenticated', this.user);
        }).catch(error => { 
            console.log("You are not logged in");
            socket.emit('not_authenticated');
            console.log(error.message);
        });
    });

    socket.on('create_game', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        axios.post(api_url+'/api/games', {created_by: this.user.id}).then(response => {
            games.createGame(response.data, this.user);
            // Notifications to the client
            socket.join(response.data.id);
            socket.emit('my_active_games_changed');
            io.emit('lobby_changed');
            console.log('Updating Lobby');
        }).catch(error => {
            console.log(error);
        });        

    });


    socket.on('join_game', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        let game = games.gameByID(data.gameID);
        if(game == null || game === undefined || game.checkGameEnded()){
            console.log("game invalid");
            socket.emit('invalid_play', {'type': 'Invalid_Game', 'game': null});
            return;
        }
        axios.patch(api_url+'/api/games/'+data.gameID+'/join', {user_id: this.user.id}).then(response => {
            console.log("Response:");
            console.log(response.data);
            let gameID = data.gameID;
            games.joinGame(gameID, this.user);
            socket.join(gameID);
            io.to(data.gameID).emit('my_active_games_changed');
            io.emit('lobby_changed');
            console.log('client joined game');
        }).catch(error => {
            console.log(error);
        });        


    });

    socket.on('start_game', function (data){
        if( this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        let game = games.gameByID(data.gameID);
        if(game == null || game == undefined || game.checkGameEnded()){
            socket.emit('invalid_play', {'type': 'Invalid_Game', 'game': null});
            return;
        }

        let playerTeams = [];
        for(let i=0; i< game.players.length; i++){
            playerTeams.push({id: game.players[i].id, team:game.players[i].team});
        }
        //console.log(playerTeams);
        axios.patch(api_url+'/api/games/'+data.gameID+'/start', {playerTeams:playerTeams}).then(response => { 
            console.log(response.data);   
            game.startGame(response.data);       
            if(game.players.length < 4){
                return;
            }
            //dar 10 cartas a cada jogador
            let time = 0;
            for(let i = 0; i<game.players.length ; i++){
                time +=500;
                setTimeout(function (){
                    game.dealCards();
                    io.to(data.gameID).emit('dealed_cards_changed', game);
                }, time);
            }
            io.to(data.gameID).emit('my_active_games_changed');
            io.emit('lobby_changed');
        }).catch(error => {
            console.log(error);
        });        
    });
    socket.on('play', function (data){
        let game = games.gameByID(data.gameID);
        if(game == null || game == undefined || game.checkGameEnded()){
            socket.emit('invalid_play', {'type': 'Invalid_Game', 'game': null});
            return;
        }
        if(game.players[game.currentPlayerIndex].id == data.player.id){
            game.play( data.player, data.card);
            io.to(game.gameID).emit('game_changed', game);
            
            if(game.checkGameEnded(game.id)){

                axios.patch(api_url+'/api/games/' + game.gameID + '/endGame', game.getObjectEndGame()).then(response => {
                    console.log('Game Terminated');
                    console.log(response.data);
                    io.to(game.gameID).emit('game_changed', game);
                    
                }).catch(error => {
                    console.log(error);
                });     
            }
        }else {
            socket.emit('invalid_play', {'type': 'Invalid_Play', 'game': game});
            return;
        }
    });

    socket.on('question_play', function (data){    
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        let game = games.gameByID(data.gameID);
        if(game == null || game == undefined || game.checkGameEnded()){
            socket.emit('invalid_play', {'type': 'Invalid_Game', 'game': null});
            return;
        }
        game.questionPlay(data.userID);
        axios.patch(api_url+'/api/games/'+game.gameID+'/endGame', game.getObjectEndGame()).then(response => {
            console.log('Game Terminated by question');
            console.log(response.data);
            io.to(game.gameID).emit('game_changed', game);

        }).catch(error => {
            console.log(error);
        });

        io.to(data.gameID).emit('my_active_games_changed');
        io.emit('lobby_changed');

    });

    socket.on('close_game', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        if(games.closeGame(data)){
            io.to(data.gameID).emit('my_active_games_changed');
            io.emit('lobby_changed');
        }

    });

    socket.on('leave_game', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        games.leaveGame(data.gameID , data.userID);
        io.to(data.gameID).emit('my_active_games_changed');
        io.emit('lobby_changed');

    });

    socket.on('get_game', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        let game = games.gameByID(data.gameID);
        socket.emit('game_changed', game);
    });

    socket.on('get_my_activegames', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        var my_games= games.getConnectedGamesOf(this.user.id);
        socket.emit('my_active_games', my_games);
    });

    socket.on('get_my_lobby_games', function (data){
        if(this.user === undefined || this.user === null){
            console.log("Unauthorized");
            return;
        }
        var my_games= games.getLobbyGamesOf(this.user.id);
        socket.emit('my_lobby_games', my_games);
    });

});