<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Login User</h1>
                <login-form @login-click="loginUser"></login-form>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
import LoginForm from './loginForm.vue';

export default {
    data: function(){
        return {

        }
    },
    methods: {
        loginUser:function(user){
            axios.post('/api/loginUser', { email: user.email, password: user.password })
            .then(function(response){
                console.log(response);
                window.localStorage.setItem("access_token", response.data.access_token);
                self.$router.push("/statistics");
            }).catch(error => {
                console.log(error);
            });
        },


    },
    components: {
        'login-form': LoginForm
    },
}
</script>

<style scoped>

</style>
