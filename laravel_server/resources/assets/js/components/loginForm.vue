<template>
   <form @submit.prevent="login(user)">
       <div class="text-left">
           <div class="form-group">
               <label for="email_nickname">Email or Nickname</label>
               <input type="text" class="form-control" id="email" placeholder="Your email or nickname" v-model="user.email">
           </div>

           <div class="form-group">
               <label for="password">Password</label>
               <input type="password" class="form-control" id="password" placeholder="Your password" v-model="user.password">
           </div>
           <div class="text-center">
               <button class="btn btn-primary">Login</button>
               <br><br>
               <router-link class="btn btn-default" to="/register">Register</router-link>

           </div>
       </div>
   </form>
</template>
<script type="text/javascript">


export default {
    data: function(){
        return {
           user: {
            email: null,
            password: null,

        },
    }
},

methods: {
  login(user) {
    this.$emit('login-click', this.user);
},
},
mounted() {

}

}
</script>

<style scoped>

</style>
