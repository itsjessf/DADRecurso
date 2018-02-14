<template>
    <div  v-if="adminLoaded"  >
        <div class="jumbotron">
            <h2>Admin Account Management</h2>
        </div>
        <h2>Platform Email Configuration</h2>
        <form @submit.prevent="updatePlatformEmail()">
            <div v-if="platformEmailLoaded" class="form-group">
                <label for="inputEmailPlatform">New platform email:</label>
                <input type="email" class="form-control" name="email" id="inputEmailPlatform" placeholder="New Platform email address" v-model.trim="platform_email"/>
            </div>
            <div class="text-left">
                <button class="btn btn-primary">Submit</button>
            </div>
        </form>
        <br>
        <h2>Admin Management</h2>
        <form @submit.prevent="updateAdminAccount()">
            <div class="form-group">
                <label for="inputEmailAdmin">New admin email:</label>
                <input type="email" class="form-control" name="email" id="inputEmailAdmin" placeholder="New Platform email address" v-model.trim="admin.email"/>
            </div>
            <div class="form-group">
                <label for="password">Old Password</label>
                <input id="password" type="password" class="form-control" placeholder="Type old password" v-model.trim="admin.password_old">
            </div>
            <div v-if="adminLoaded" class="form-group">
                <label for="newPassword">New Password</label>
                <input id="newPassword" type="password" class="form-control" placeholder="Type new password"  v-model.trim="admin.password">
            </div>
            <div class="form-group">
                <label for="newPasswordConfirmation">Repeat New Password</label>
                <input id="newPasswordConfirmation" type="password" class="form-control"  placeholder="Repeat new password" v-model.trim="admin.password_confirmation">
            </div>
            <div class="text-left">
                <button class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</template>

<script type="text/javascript">
export default {
    data: function(){
        return{
            platform_email:null,
            platformEmailLoaded:false,
            admin:null,
            adminLoaded:false,
        }
    },
    methods: {
        getCurrentUser: function(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.get('/api/user', config)
            .then(response=>{
                this.admin = response.data;
                this.adminLoaded = true;
            }).catch(error => {
                console.log(error);                 
            });
        },
        getPlatformEmail: function(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.get('/api/config/getPlatformEmail', config)
            .then(response=>{
                this.platform_email = response.data.platform_email;
                this.platformEmailLoaded = true;
            }).catch(error => {
                console.log(error);                 
            });
        },

        updatePlatformEmail: function(){
            this.platformEmailLoaded = false;
            //Update do email da plataforma
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.put('/api/config/updatePlatformEmail', {platform_email:this.platform_email}, config)
            .then(response=>{
                this.platformEmail = response.data.platform_email;
                this.platformEmailLoaded = true;
            }).catch(error => {
                console.log(error);                 
            });
        },
        updateAdminAccount: function(){
            this.adminLoaded = false;
            //Update da conta do Admin
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.put('api/users/admin/'+this.admin.id, this.admin, config)
            .then(response=>{
                console.log(response.data);
                this.getCurrentUser();
            }).catch(error => {
                console.log(error.response.data);
            });
        },

    },
    mounted() {
        this.getCurrentUser();
        this.getPlatformEmail();
    }
}
</script>

<style scoped>

</style>
