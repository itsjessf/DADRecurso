<template>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-2">
				<img :src="image" class="img-responsive">
			</div>
			<div class="col-md-8">
				<input type="file" v-on:change="onFileChange" class="form-control">
			</div>
			<div class="col-md-2">
				<button class="btn btn-success btn-block" v-on:click.prevent="upload">Upload</button>
			</div>
		</div>
	</div>
</template>
<style scoped>
img{
	max-height: 36px;
}
</style>
<script>
export default{
	props:['publicPath'],
	data(){
		return {
			image: '',
			fileName: '',
		}
	},
	methods: {
		onFileChange(e) {
			let files = e.target.files || e.dataTransfer.files;
			if (!files.length)
				return;
			this.createImage(files[0]);
		},
		createImage(file) {
			let reader = new FileReader();
			let vm = this;
			reader.onload = (e) => {
				vm.image = e.target.result;
			};
			reader.readAsDataURL(file);
		},
		upload(){
			var header = { 
				headers: { 
					'Authorization': 'Bearer ' + window.localStorage.getItem('access_token')
				}};
				var data = {"image" : this.image, "publicPath":this.publicPath};
				axios.post('api/upload', data, header).then(response=>{
					if(response.status == 201){
						this.fileName = response.data;
						this.$emit('card-front-uploaded-click', this.fileName);
					}
				}).catch(error => {
					console.log(error.response.data.errors);
				});


			}
		}
	}
	</script>