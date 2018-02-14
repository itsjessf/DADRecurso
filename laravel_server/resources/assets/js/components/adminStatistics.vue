<template>
    <div class="container">
        <h1>Global Statistics</h1>
        <div class="row">
            <h4>Top Players (Total Games)</h4>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nickname</th>
                    <th>Total Games</th>
                    <th>Total Draws</th>
                    <th>Total Wins</th>
                    <th>Total Defeats</th>

                </tr>
            </thead>
            <tbody>
                <template v-for="player in listPlayers">
                    <tr>
                        <td>{{player.id}}</td>
                        <td>{{player.nickname}}</td>
                        <td> {{player.total_games_played}}</td>
                        <td> {{player.draws}}</td>
                        <td> {{player.wins}}</td>
                        <td> {{player.defeats}}</td>

                    </tr>
                </template>
            </tbody>
        </div>
        <div class="row">
            <h4>Count games daily</h4>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="day in gamesDaily">
                    <tr>
                        <td>{{day.date}}</td>
                        <td>{{day.count}}</td>
                    </tr>
                </template>
            </tbody>
        </div>
        <div class="row">
            <div class="col-md-2"><h4 v-if="totalGames != null">Total Games:</h4> {{totalGames}}</div>
            <div class="col-md-2"><h4 v-if="totalUsers != null">Total Users:</h4> {{totalUsers}}</div>
        </div>
    </div>
</template>

<script type="text/javascript">


export default {
    data: function(){
        return {
            listPlayers: [],
            gamesDaily:[],
            user: null,
            totalGames: null,
            totalUsers:null,
        }

    },
    methods: {
        authenticate(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token_admin')
                }
            };
            axios.get('/api/user', config).then(response =>{
                this.user = response.data;
                this.getListPlayers();
                this.getListGamesDaily();
            }).catch(error => { 
                this.user = null;
                console.log(error.message);
            });
        },
        getListPlayers() {
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token_admin')
                }
            };
            axios.get('/api/statistics/player/list/', config)
            .then(response =>{
                console.log(response.data.data);
                this.listPlayers = response.data.data;
            }).catch(error => {
                console.log(error);
            });
        },
        getListGamesDaily() {
             let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token_admin')
                }
            };
            axios.get('/api/statistics/gamesByDay/', config)
            .then(response =>{
                console.log(response.data.data);
                this.gamesDaily = response.data.data;
            }).catch(error => {
                console.log(error);
            });
        },
        getTopPoints() {
            axios.get('/api/statistics/top/points/5/')
            .then(response =>{
                console.log(response.data.data);
                this.topPoints = response.data.data;
            }).catch(error => {
                console.log(error);
            });
        },

        getTotalGames() {
            axios.get('/api/statistics/totalGames')
            .then(response =>{
                this.totalGames = response.data;
            }).catch(error => {
                console.log(error);
            });
        },
        getTotalUsers() {
            axios.get('/api/statistics/totalUsers')
            .then(response =>{
                this.totalUsers = response.data;
            }).catch(error => {
                console.log(error);
            });
        },
    },
    beforeMount() {
        this.authenticate();
    },
    mounted() {
        this.getTotalGames();
        this.getTotalUsers();
    }

}
</script>

<style scoped>

</style>
