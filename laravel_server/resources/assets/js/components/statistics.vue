<template>
    <div class="container">
        <h1>Global Statistics</h1>
        <div class="row">
            <div class="col-md-4">
                <h4>Top Players (Total Games)</h4>
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Total Games</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="player in topTotalGames">
                        <tr>
                            <td>{{player.nickname}}</td>
                            <td> {{player.total_games_played}}</td>
                        </tr>
                    </template>
                </tbody>
            </div>
            <div  class="col-md-4">
                <h4>Top Players (Points)</h4>
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Total Points</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="player in topPoints">
                        <tr>
                            <td>{{player.nickname}}</td>
                            <td>{{player.total_points}}</td>
                        </tr>

                    </template>
                </tbody>
            </div>
            <div class="col-md-4">
                <h4>Top Players (Average)</h4>
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Average</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="player in topAverage">
                        <tr>
                            <td>{{player.nickname}}</td>
                            <td>{{player.average}}</td>
                        </tr>

                    </template>
                </tbody>

                <div>
                </br>

            </div>

        </div>
        <div class="row">
            <div class="col-md-2"><h4 v-if="totalGames != null">Total Games:</h4> {{totalGames}}</div>
            <div class="col-md-2"><h4 v-if="totalUsers != null">Total Users:</h4> {{totalUsers}}</div>
        </div>
        <h1 v-if="user!= null">Your Statistics</h1>
        <div class="row" v-if="user!= null">
            <div class="col-md-2">
                <h5 v-if="userTotalGames != null">Total games played: {{userTotalGames}}</h5>  
            </div>             
            <div class="col-md-2"><h5 v-if="userTotalWins != null">Wins: {{userTotalWins}}</h5></div>
            <div class="col-md-2"><h5 v-if="userTotalDraws != null">Draws: {{userTotalDraws}}</h5></div>
            <div class="col-md-2"><h5 v-if="userTotalDefeats != null">Defeats: {{userTotalDefeats}}</h5></div>
            <div class="col-md-2"><h5 v-if="playerTotalPoints != null">Total Points: {{playerTotalPoints}}</h5> </div>
            <div class="col-md-2"><h5 v-if="userAverageScore != null">Average: {{userAverageScore}}</h5> </div>                   
        </div>
    </div>
</div>
</div>
</template>

<script type="text/javascript">


export default {
    data: function(){
        return {
            topTotalGames: [],
            topPoints: [],
            topAverage: [],
            user: null,
            totalGames: null,
            totalUsers:null,
            userTotalGames:null,
            playerTotalPoints: null,
            userAverageScore: null,
            userTotalWins:null,
            userTotalDraws:null,
            userTotalDefeats:null,
        }

    },
    methods: {
        authenticate(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };
            axios.get('/api/user', config).then(response =>{
                this.user = response.data;
                this.getUserGames();
                //this.getUserTotalScore();
                //this.getUserAverageScore();
                this.playerTotalPoints = this.user.total_points;
                this.userAverageScore = this.user.average;
                this.getUserWins();
                this.getUserDefeats();
                this.getUserDraws();
                console.log(this.user);
            }).catch(error => { 
                this.user = null;
                console.log(error.message);
            });
        },
        getTopTotalGames() {
            axios.get('/api/statistics/top/totalGames/5/')
            .then(response =>{
                console.log(response.data.data);
                this.topTotalGames = response.data.data;
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
        getTopAverage() {
            axios.get('/api/statistics/top/average/5/')
            .then(response =>{
                console.log(response.data.data);
                this.topAverage = response.data.data;
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
        //Authenticated user
        getUserGames(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };

            axios.get('/api/statistics/player/' + this.user.id + '/totalGames', config)
            .then(response =>{
                this.userTotalGames = response.data;
                console.log(this.userTotalGames);
            }).catch(error => {
                console.log(error);
            });
        },
        getUserTotalScore(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };

            axios.get('/api/statistics/player/' + this.user.id + '/totalScore', config)
            .then(response =>{
                this.userTotalScore = response.data;
                console.log(this.userTotalScore);
            }).catch(error => {
                console.log(error);
            });
        },
        getUserAverageScore(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };

            axios.get('/api/statistics/player/' + this.user.id + '/averageScore', config)
            .then(response =>{
                this.userAverageScore = response.data;
                console.log(this.userAverageScore);
            }).catch(error => {
                console.log(error);
            });
        },
        getUserWins(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };
            axios.get('/api/statistics/player/' + this.user.id + '/totalWins', config)
            .then(response =>{
                this.userTotalWins = response.data;
                console.log(this.userTotalWins);
            }).catch(error => {
                console.log(error);
            });

        },
        getUserDraws(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };
            axios.get('/api/statistics/player/' + this.user.id + '/totalDraws', config)
            .then(response =>{
                this.userTotalDraws = response.data;
                console.log(this.userTotalDraws);
            }).catch(error => {
                console.log(error);
            });

        },
        getUserDefeats(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
                }
            };
            axios.get('/api/statistics/player/' + this.user.id + '/totalDefeats', config)
            .then(response =>{
                this.userTotalDefeats = response.data;
                console.log(this.userTotalDefeats);
            }).catch(error => {
                console.log(error);
            });

        },

    },
    beforeMount() {
        this.authenticate();
    },
    mounted() {
        this.getTopTotalGames();
        this.getTopPoints();
        this.getTopAverage();
        this.getTotalGames();
        this.getTotalUsers();
    }

}
</script>

<style scoped>

</style>
