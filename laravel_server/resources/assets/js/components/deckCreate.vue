<template>
    <div>
        <div class="jumbotron">
            <h2>Create Deck</h2>
        </div>
        <div class="form-group">
            <label for="inputName">Name of the new deck</label>
            <input type="text" class="form-control" v-model="deck.name" name="name" id="inputName" placeholder="Name of the deck"/>
            <div v-if="errorsName != null" style="color:red" ><strong>{{ errorsName }}</strong></div>
        </div>
        <div class="form-group">
            <label for="inputHiddenFaceImagePath">Chose an existing card back image:</label>
            <div class="form-group">
                <input type="radio" v-model="deck.hidden_face_image_path" name="hiddenFaceImagePath" id="inputHiddenFaceImagePath" v-bind:value="'img/cardBacks/semFace.png'"/>
                <img height="70" width="50" v-bind:src="'img/cardBacks/semFace.png'"><br>
                <input type="radio" v-model="deck.hidden_face_image_path" name="hiddenFaceImagePath" id="inputHiddenFaceImagePath" v-bind:value="'img/cardBacks/teste.png'"/>
                <img height="70" width="50" v-bind:src="'img/cardBacks/teste.png'"><br>
                <input type="radio" v-model="deck.hidden_face_image_path" name="hiddenFaceImagePath" id="inputHiddenFaceImagePath" v-bind:value="'img/cardBacks/1517777780_5a77737468bcf.png'"/>
                <img height="70" width="50" v-bind:src="'img/cardBacks/1517777780_5a77737468bcf.png'"><br>
            </div>
            <div v-if="errorsImagePath != null" style="color:red" ><strong>{{ errorsImagePath }}</strong></div>
        </div>
        <div class="col-md-12 text-left">
            <div class="panel panel-default">
                <div class="panel-heading">Or upload a new image</div>
                <div class="panel-body">
                    <file-upload :publicPath="publicPath" @card-back-uploaded-click="updateCardBackList()"></file-upload>
                </div>
            </div>
        </div>
        <div>
            <div class="form-group">
                <a class="btn btn-default" v-on:click.prevent="createDeck(deck)">Create</a>
                <a class="btn btn-default" v-on:click.prevent="cancelCreate()">Cancel</a>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">

import FileUpload from './fileUpload.vue';

export default {
  props: ['decks'],
  data: function(){
    return {
        errorsName:null,
        errorsImagePath:null,
        deck:{},
        cardBacks:[],
        publicPath : 'img/cardBacks/',
    }
},
methods: {
    createDeck:function(deck){
        let config = {
            headers: {
                'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
            }
        };
        axios.post('api/decks', deck, config)
        .then(response => {
            console.log("Created Deck");
            this.$emit('create-deck-click', deck);
        }).catch((error)=>{
            console.log(error.response.data.errors);
            this.errors(error.response.data.errors);
        });
    },
    getCardBackList: function(){
        
        axios.get('api/images/cardBackPaths')
        .then(response => {
            this.cardBacks = response.data;
            console.log(this.cardBacks[0]);
        }).catch(function(error){
            console.log(error);
        });

    },
    cancelCreate: function(){
        this.$emit('cancel-click');
    },
    updateCardBackList: function(){
        this.getCardBackList();
    },
    errors(error){
        this.errorsName = null;
        this.errorsImagePath = null;
        if (typeof(error.name) != "undefined"){
            this.errorsName = error.name[0];
        }
        if (typeof(error.hidden_face_image_path) != undefined){
            this.errorsImagePath = "Please chose a card back or upload your own";
        }
    }, 
},
components: {
    'file-upload': FileUpload
},
mounted(){
    this.getCardBackList();
},
}
</script>

<style scoped>  

</style>