<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Login Admin</h1>
                <login-form @login-click="loginAdmin"></login-form>
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
        loginAdmin:function(user){
            let self = this;
            axios.post('/api/loginAdmin', { email: user.email, password: user.password })
            .then(function(response){
                console.log(response);
                window.localStorage.setItem("access_token_admin", response.data.access_token);
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
