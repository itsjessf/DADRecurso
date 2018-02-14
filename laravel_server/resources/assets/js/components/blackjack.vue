<template>
    <div>
        <div>
            <h3 class="text-center">{{ title }}</h3>
            <br>
            <h2>Welcome</h2>
            <h3 class="text-center">Lobby</h3>
            <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame()">Create a New Game</button></p>
            <hr>
            <h4>Pending games (<a @click.prevent="loadLobby()">Refresh</a>)</h4>
            <lobby @join-click="join" :games="lobbyGames"></lobby>
            <template v-for="game in activeGames">
                <game :user="user" :game="game"></game>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
import Lobby from './lobby.vue';
import GameBlackJack from './game-blackjack.vue';

export default {
    data: function(){
       return {
        title: 'Black Jack',
        lobbyGames: [],
        activeGames: [],
        user : null,
        currentGame:null,
    }
},
sockets:{
    not_authenticated(){
        this.$router.push("/loginUser");
    },
    authenticated(user){
        this.user = user;
        console.log(this.user);
    },
    connect(){
        console.log('socket connected');
        this.socketId = this.$socket.id;
    },
    discconnect(){
        console.log('socket disconnected');
        this.socketId = "";
    },
    lobby_changed(){
        console.log("Lobby Changed");
        this.loadLobby();
    },
    my_active_games_changed(){
        this.loadActiveGames();
    },
    my_active_games(games){
        this.activeGames = games;
    },
    my_lobby_games(games){
        this.lobbyGames = games;
    },
    game_changed(game){
        console.log(game);
        for (var lobbyGame of this.lobbyGames) {
            if (game.gameID == lobbyGame.gameID) {
                Object.assign(lobbyGame, game);
                break;
            }
        }
        for (var activeGame of this.activeGames) {
            if (game.gameID == activeGame.gameID) {
                Object.assign(activeGame, game);
                break;
            }
        }
    },
    dealed_cards_changed(game){
        for (var activeGame of this.activeGames) {
            if (game.gameID == activeGame.gameID) {
                Object.assign(activeGame, game);
                break;
            }
        }
    },

},

methods: {
    loadLobby(){
        console.log("Loading Lobby");
        this.$socket.emit('get_my_lobby_games');
    },
    loadActiveGames(){
        this.$socket.emit('get_my_activegames');
    },
    createGame(){
        this.$socket.emit('create_game');
    },
    join(game){
        this.$socket.emit('join_game', game);

    }
},
components: {
    'lobby': Lobby,
    'game': GameBlackJack,
},

beforeMount() {
    this.$socket.emit('authentication', window.localStorage.getItem('access_token_admin'));
},

mounted() {
    this.loadLobby();
}

}
</script>

<style>

</style>
