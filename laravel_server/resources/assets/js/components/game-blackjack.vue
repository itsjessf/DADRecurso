<template>
	<div class="gameseparator">
        <div>
            <h2 class="text-center">Game {{ game.gameID }}</h2>
            <br>
        </div>
        <div class="game-zone-content">
            <div class="alert" :class="alerttype" v-show ="!game.gameStarted">
                <strong >{{ standbyMessage }}</strong>
                <button class="btn btn-xs btn-danger" v-show="game.gameStarted" v-on:click.prevent="leaveGame(game,user)">Leave Game</button>
            </div>
            <div>
                <template v-if= "user.id == game.gameOwner">
                    <button class="btn btn-xs btn-success" v-show="game.players.length == 4" v-if="!game.gameStarted" v-on:click.prevent="startGame(game)">Start Game</button>
                </template>
            </div>
            <div v-show ="game.gameStarted">
                <div class="alert" :class="alerttype">
                    <strong>{{gameMessage}}</strong>
                    <br>
                    <button class="btn btn-xs btn-danger" v-show="game.gameEnded" v-on:click.prevent="closeGame(game)">Close Game</button>
                </div>
                <template v-if="!game.gameEnded">
                    <button class="btn btn-xs btn-success" v-show="!game.gameEnded" v-on:click.prevent="questionPlay(game,user)">Question</button>
                    <div class="board">
                        <div class="line" v-if="!game.trumpCardHidden">
                            <strong>TRUMP CARD</strong>
                            <img v-bind:src="game.trumpCard.path">
                        </div>
                    </div>
                    <div class="board">
                        <div class="line" v-for="currentCard in game.currentPlay">
                            <img v-bind:src="currentCard.path">
                        </div>
                    </div>
                    <div class="board">
                        <div v-for="player in game.players">
                            <div v-if ="user.id == player.id" class="line" v-for="card in player.cards" >
                                <img v-bind:src="card.path" v-on:click="play(game,card,player)">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: ['game','user'],
    data: function(){
     return {
        cardBack: null,
    }
},
computed: {
    standbyMessage(){
        if (!this.game.gameStarted) {
            return "Waiting for other players";
        }
    },
    gameMessage(){    
        if(this.game.gameEnded){
            for(var i=0; i<this.game.players.length; i++){
                if(this.game.players[i].id == this.user.id && this.game.players[i].team == this.game.team_winner){
                    if(this.game.team_winner == 1){
                        return "Your team won, with "+this.game.team1_cardpoints+" !";
                    }else{
                        return "Your team won with "+this.game.team2_cardpoints+" !";
                    }

                }
            }
            return "Your team lost";
            
        }
        if(this.game.players[this.game.currentPlayerIndex].id == this.user.id){              
            return "Its your turn";              
        }else{
            return "Wait for your turn";
        }

    },
    alerttype(){
        if(this.game.gameEnded){
            for(var i=0; i<this.game.players.length; i++){
                if(this.game.players[i].id == this.user.id && this.game.players[i].team == this.game.team_winner){
                    return "alert-success";              
                }
            }
            return "alert-warning";
        }
        if (!this.game.gameStarted) {
            return "alert-warning";
        } else if (this.game.gameEnded) {
            if(this.game.players[this.game.currentPlayerIndex].id == this.user.id){              
                return "alert-success";              
            }else{
                return "alert-warning";
            }
        }
    },
},
methods: {
    startGame(game){
        this.$socket.emit('start_game', {gameID: game.gameID});
    },
    getCardBack(game) {
        axios.get('/api/decks/'+game.deckID)
        .then((response)=>{
            this.cardBack = response.data.data.hidden_face_image_path;

        }).catch(error => {
            console.log(error);
        });
    },
    play(game,card,player){
        if(game.currentPlay != 4){
            if(game.players[game.currentPlayerIndex].id == this.user.id){
                this.$socket.emit('play', {gameID: game.gameID, card:card, player:player});
            }
        }

    },
    questionPlay(game,user){
        this.$socket.emit('question_play', {gameID: game.gameID, userID: user.id});
    },
    closeGame (game){
        this.$socket.emit('close_game', game);
    },
    leaveGame (game,user){
        console.log("Trying to leave");
        this.$socket.emit('leave_game', {gameID: game.gameID, userID: user.id});
    },

},
mounted(){
    this.getCardBack(this.game);
}

}
</script>

<style scoped>
.gameseparator{
    border-style: solid;
    border-width: 2px 0 0 0;
    border-color: black;
}
</style>
