<template>
	<div class="container">
		<div class="row">
			<div v-if="userLoaded" class="col-md-8 col-md-offset-2">
				<h1>Manage Account</h1>
				<form @submit.prevent="updateUser(user)">
					<div class="text-left">
						<div class="form-group">
							<label for="name">Full Name</label>
							<input id="name" type="text" class="form-control" name="name" v-model.trim="user.name">
							<div style="color:red" v-if="errorsName != null">
								<strong>{{ errorsName }}</strong>
							</div>
						</div>
						<div class="form-group">
							<label for="nickname">Nickname</label>
							<input id="nickname" type="text" class="form-control" v-model.trim="user.nickname">
							<div style="color:red" v-if="errorsNickname != null">
								<strong>{{ errorsNickname }}</strong>
							</div>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input id="email" type="email" class="form-control" v-model.trim="user.email">
							<div style="color:red" v-if="errorsEmail != null">
								<strong>{{ errorsEmail }}</strong>
							</div>
						</div>
						<div class="form-group">
							<br><br>
							<h1>Change current password:</h1>
							<label for="password">Old Password</label>
							<input id="password" type="password" class="form-control" placeholder="Type old password" v-model.trim="user.password_old">
							<div style="color:red" v-if="errorsOldPassword != null">
								<strong>{{ errorsOldPassword }}</strong>
							</div>
						</div>
						<div class="form-group">
							<label for="newPassword">New Password</label>
							<input id="newPassword" type="password" class="form-control" placeholder="Type new password" v-model.trim="user.password">
							<div style="color:red" v-if="errorsPassword != null">
								<strong>{{ errorsPassword }}</strong>
							</div>
						</div>
						<div class="form-group">
							<label for="newPasswordConfirmation">Repeat New Password</label>
							<input id="newPasswordConfirmation" type="password" class="form-control"  placeholder="Repeat new password" v-model.trim="user.password_confirmation">
							<div style="color:red" v-if="errorsPasswordConfirmation != null">
								<strong>{{ errorsPasswordConfirmation }}</strong>
							</div>
						</div>
					</div>
					<div class="text-center">
						<button class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">

export default {
	data: function(){
		return{
			userLoaded:false,
			user:null,
			errorsName:null,
			errorsNickname:null,
			errorsEmail:null,
			errorsOldPassword:null,
			errorsPassword:null,
			errorsPasswordConfirmation:null,

		}
	},
	methods: {
		getCurrentUser: function(){
			let config = {
				headers: {
					'Authorization': 'Bearer ' + window.localStorage.getItem("access_token")
				}
			};
			axios.get('/api/user', config)
			.then(response=>{
				console.log(response.data);
				this.user = response.data;
				this.userLoaded = true;
			}).catch(error => {
				console.log(error);					
			});
		},
		updateUser: function(){
			let config = {
				headers: {
					'Authorization': 'Bearer ' + window.localStorage.getItem("access_token")
				}
			};
			axios.put('api/users/'+this.user.id, this.user, config)
			.then(response=>{
				console.log(response.data);
				this.getCurrentUser();
				this.resetErrors();
			}).catch(error => {
				console.log(error.response.data);
				this.errors(error.response.data);
			});
		},
		errors(error){
			this.resetErrors();
			if (typeof(error["password"]) != "undefined"){
				this.errorsOldPassword = error["password"];
			}
			else if (typeof(error.errors.name) != "undefined"){
				this.errorsName = error.errors.name[0];
			}
			else if (typeof(error.errors.nickname) != "undefined"){
				this.errorsNickname = error.errors.nickname[0];
			}
			else if (typeof(error.errors.email) != "undefined"){
				this.errorsEmail = error.errors.email[0];
			}
			else if (typeof(error.errors.password_confirmation) != "undefined"){
				this.errorsPassword = error.errors.password_confirmation[0];
			}
		},
		resetErrors(){
			this.errorsName = null;
			this.errorsNickname = null;
			this.errorsEmail = null;
			this.errorsOldPassword=null;
			this.errorsPassword=null;
			this.errorsPasswordConfirmation=null;
		}
	},
	mounted() {
		this.getCurrentUser();
	}
}
</script>

<style scoped>

</style>
