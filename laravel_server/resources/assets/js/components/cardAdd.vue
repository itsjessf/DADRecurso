<template>
    <div class ="col-md-12">
        <div class="jumbotron">
            <h2>Add Card</h2>
        </div>
        <div v-if="missingCards == null">
            <div>There are no cards missing from this deck</div>
            <a class="btn btn-xs btn-danger" v-on:click.prevent="closeCardAdd()">Close</a> 
        </div>
        <div v-else>
            <label for="choseCard"> Chose a card that is missing from this deck:</label>
            <select v-model="card">
                <option  v-for="missingCard in missingCards" v-bind:value="missingCard" >{{missingCard[0]}} of {{missingCard[1]}}s </option>
            </select>
            <div class="form-group" v-if="adding">
                <label for="inputImagePath">Uploaded card image:</label>
                <input type="radio" v-model="card.path" name="imagePath" id="inputImagePath" v-bind:value="cardFrontImagePath"/>
                <img height="70" width="50" v-bind:src="cardFrontImagePath"><br>
            </div>
            <div class="col-md-12 text-left">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload an image for this card (or don't for the default image)</div>
                    <div class="panel-body">
                        <file-upload :publicPath="publicPath" @card-front-uploaded-click="updateCardFrontImage"></file-upload>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <a class="btn btn-xs btn-success" v-on:click.prevent="addCard(deck)">Add</a>
                <a class="btn btn-xs btn-danger" v-on:click.prevent="closeCardAdd()">Cancel</a> 
            </div>
        </div>         
    </div>
</template>

<script type="text/javascript">

import FileUpload from './fileUpload.vue';

export default {
    props: ['deck'],
    data: function(){
        return {
            errorsName:null,
            errorsImagePath:null,
            missingCards: null,
            card:{},
            publicPath : 'img/customCardFronts/',
            adding : false,
            cardFrontImagePath : '',
        }
    },
    methods: {
        getMissingCardsFromDeck(deck){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.get('api/decks/'+deck.id+'/missingCards', config)
            .then(response => {
                console.log(response.data);
                if(response.data.length == 0){
                    //Se não faltam cartas o deck fica completo e dá update no deckList
                    this.missingCards = null;
                    let config = {
                        headers: {
                            'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                        }
                    };
                    axios.patch('api/decks/'+deck.id+'/complete', {}, config)
                    .then(response => {
                        console.log(response.data);
                        this.$emit('complete-deck-update');
                    }).catch((error)=>{
                        console.log(error);
                    });
                }else{
                    // Se faltam cartas guarda-as
                    this.missingCards = response.data; 
                }
            }).catch(function(error){
                console.log(error);
            });
        },
        updateCardFrontImage(fileName){
            this.cardFrontImagePath = this.publicPath+fileName;
            this.adding = true;
        },
        closeCardAdd(){
            this.$emit('card-add-cancel');
        },
        addCard(deck){
            var newCard = {};
            newCard.value = this.card[0];
            newCard.suite = this.card[1];
            //Se foi escolhida a imagem que se deu upload, esse vai ser o caminho da imagem,
            //se não, fica com a default image
            if(this.card.path){
                newCard.path = this.card.path;  
            }else{
                newCard.path = 'img/'+this.card[2]+'.png';
            }
            newCard.deck_id = deck.id;

            let config = {
                headers: {
                    'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
                }
            };
            axios.post('api/cards/store', newCard, config)
            .then(response => {
                console.log(response.data);
                this.getMissingCardsFromDeck(deck);
                // update à lista de cartas ( nova carta aparece no fim da lista)
                this.$emit('card-list-update', deck);
                this.closeCardAdd();
            }).catch(function(error){
                console.log(error);
            });
        },
        errors(error){
            this.errorsName = null;
            this.errorsImagePath = null;
            if (typeof(error.name) != "undefined"){
                this.errorsName = error.name[0];
            }
            if (typeof(error.hidden_face_image_path) != undefined){
                this.errorsImagePath = "Please upload a card back ";
            }
        }, 
    },
    components: {
        'file-upload': FileUpload
    },
    mounted(){
        this.getMissingCardsFromDeck(this.deck);
    },

}

</script>

<style scoped>  

</style>