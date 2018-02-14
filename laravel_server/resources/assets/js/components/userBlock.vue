<template>
	<div>
		
		<template v-if="user.blocked == 0">
			<div class="jumbotron">
				<h2>Block User</h2>
			</div>
			<div class="form-group">
				<label for="reasonBlocked">Reason why blocked:</label>
				<input type="text" class="form-control" name="reasonBlocked" id="reasonBlocked" placeholder="Please write a short text explaining the reason why this user diserves to be Blocked" v-model="user.reason_blocked"/>
			</div>
		</template>
		<template v-else>
			<div class="jumbotron">
				<h2>Reactivate User</h2>
			</div>
			<div class="form-group">
				<label for="reasonReactivated">Reason why reactivated:</label>
				<input type="text" class="form-control" name="reasonReactivated" id="reasonReactivated" placeholder="Please write a short text explaining the reason why this user diserves to be Reactivated" v-model="user.reason_reactivated"/>
			</div>
		</template>
		<a class="btn btn-default" v-on:click.prevent="saveReasonBlocked()">Save</a>
		<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
	</div>
</template>

<script type="text/javascript">
export default {
	props: ['user'],
	methods: {
		saveReasonBlocked: function(){
			let config = {
				headers: {
					'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
				}
			};
			
			axios.put('api/users/'+this.user.id+'/storeReasonBlock', this.user, config)
			.then(response=>{
				console.log(response.data);
				var user = response.data;
				this.$emit('block-saved', user);
				this.$emit('block-cancel');
			}).catch(function(error){
				console.log(error);
			});
		},
		cancelEdit: function(){
			this.$emit('block-cancel');
		}
	}
}
</script>

<style scoped>

</style>
