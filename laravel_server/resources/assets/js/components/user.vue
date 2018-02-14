<template>
	<div>
		<div class="jumbotron">
			<h1>{{ title }}</h1>
		</div>
		<div class="alert alert-success" v-if="showSuccess">
			<button type="button" class="close-btn" v-on:click="showSuccess=false"></button>
			<strong>{{ successMessage }}</strong>
		</div>
		<user-list :users="users" @toggle-block-user="toggleBlockUser" @delete-click="deleteUser" @message="childMessage" ref="usersListRef"></user-list>			
	</div>				
</template>

<script type="text/javascript">
import UserList from './userList.vue';

export default {
	data: function(){
		return { 
			title: 'List Users',
			showSuccess: false,
			successMessage: '',
			currentUser: null,
			users: [],

		}
	},
	methods: {
		deleteUser: function(user){
			let config = {
				headers: {
					'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
				}
			};
			axios.delete('api/users/'+user.id, config)
			.then(response => {
				this.showSuccess = true;
				this.successMessage = 'User Deleted';
				this.getUsers();
			});
		},
		toggleBlockUser: function(user){
			let config = {
				headers: {
					'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
				}
			};
			this.showSuccess = false;
			axios.post('api/users/'+user.id+'/block', {}, config)
			.then(response => {
				this.showSuccess = true;
				if(response.data.blocked == 1){
					this.successMessage = 'User Blocked';
				}else{
					this.successMessage = 'User Reactivated';
				}
				this.getUsers();
			});
		},
		getUsers: function(){
			let authentication_header = { headers: { 'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")}};		
			axios.get('api/users', authentication_header).then(response=>{
				this.users = response.data.data; 
			}).catch(function(error){
				console.log(error);
			});
		},
		childMessage: function(message){
			this.showSuccess = true;
			this.successMessage = message;
		},
	},
	components: {
		'user-list': UserList
	},
	mounted(){
		this.getUsers();
	},


}
</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>