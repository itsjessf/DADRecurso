<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Logout</h1>
                <p><em>{{message}}</em></p>
                <div class="text-left">
                    <button class="btn btn-primary"  v-on:click="logout()">Logout</button>
                    <router-link class="btn btn-default" to="/users">Cancel</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">


export default {
    data: function(){
        return {
        }
    },
    computed: {
        message(){
            return "Are you sure you want to logout?";
        }
    },
    methods: {
       logout() {
        let self = this;
        let config = {
            headers: {
                'Authorization': 'Bearer ' + window.localStorage.getItem('access_token_admin'),
            }
        };
        axios.post('api/logout', null, config)
        .then(response=>{
            if(response.status == 200){
                self.$router.push('loginUser');
                window.localStorage.removeItem('access_token');
                console.log("Token removed from Local Storage");
            }
            console.log(response);
        }).catch(error => {
            console.log(error);
        });
    }
},


}
</script>

<style scoped>

</style>
