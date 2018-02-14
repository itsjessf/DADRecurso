<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Register</h1>
                <form @submit.prevent="register(user)">
                    <div class="text-left">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="name" class="form-control" id="name" placeholder="Your name" v-model.trim="user.name">
                            <div style="color:red" v-if="errorsName != null"><strong>{{ errorsName }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="nickname">Nickname</label>
                            <input type="nickname" class="form-control" id="nickname" placeholder="Your nickname" v-model.trim="user.nickname">
                            <div style="color:red" v-if="errorsNickname != null"><strong>{{ errorsNickname }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Your email" v-model.trim="user.email">
                            <div style="color:red" v-if="errorsEmail != null"><strong>{{ errorsEmail }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Your password" v-model.trim="user.password">
                            <div style="color:red" v-if="errorsPassword != null"><strong>{{ errorsPassword }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Repeat Password</label>
                            <input type="password" class="form-control" id="password_confirmation" placeholder="Repeat password" v-model.trim="user.password_confirmation">
                            <div style="color:red" v-if="errorsPasswordConfirmation != null"><strong>{{ errorsPasswordConfirmation }}</strong></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" to="/loginUser">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">


export default {
    data: function(){
        return {
            errorsName:null,
            errorsNickname:null,
            errorsEmail:null,
            errorsPassword:null,
            errorsPasswordConfirmation:null,
            user: {
                email: null,
                password: null,
                name:null,
                nickname:null,
                password_confirmation: null,
            },
        }
    },
    methods: {
       register(user) {
         let self = this;
         axios.post('/api/register', { email: user.email, password: user.password, password_confirmation: user.password_confirmation, name: user.name, nickname: user.nickname})
         .then(function(response){
            self.$router.push('loginUser');
        }).catch(error => {
            console.log(error.response.data.errors);
            this.errors(error.response.data.errors);

        });

    },
    errors(error){
        this.errorsName = null;
        this.errorsNickname = null;
        this.errorsEmail = null;
        this.errorsPassword = null;
        this.errorsPasswordConfirmation = null;
        if (typeof(error.name) != undefined){
            this.errorsName = error.name[0];
        }
        if (typeof(error.nickname) != undefined){
            this.errorsNickname = error.nickname[0];
        }
        if (typeof(error.email) != undefined){
            this.errorsEmail = error.email[0];
        }
        if (typeof(error.password) != undefined){
            this.errorsPassword = error.password[0];
        }
        if (typeof(error.password_confirmation) != undefined){
            this.errorsPasswordConfirmation = error.password_confirmation[0];
        }
    },
},
mounted() {

}

}
</script>

<style scoped>

</style>
