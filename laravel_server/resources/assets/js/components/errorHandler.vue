<template>
    <div class="container">
        <strong style="color:red">{{errorHandler}}</strong>
    </div>
</template>

<script type="text/javascript">

export default {
    props: ['error'],
    data: function(){
        return{
        }
    },
    methods: {
        errorHandler: function(){
            axios.get('/api/config/getPlatformEmail', this.platform_email)
            .then(response=>{
                this.platform_email = response.data.platform_email;
                this.platformEmailLoaded = true;
            }).catch(error => {
                console.log(error);                 
            });
        },
    },
    mounted() {

    }
}
</script>

<style scoped>

</style>
