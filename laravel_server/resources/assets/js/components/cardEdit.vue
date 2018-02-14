<template>
    <div class ="col-md-12">
        <div class="jumbotron">
            <h2>Manage Cards</h2>
        </div>
        <a class="btn btn-xs btn-success" v-on:click.prevent="openAddCard()">Add Card</a>
        <card-add :deck="deck" v-if="addingCard" @card-add-cancel="closeCardAdd" @complete-deck-update="updateDeckList" @card-list-update="getCards"></card-add>         
        <div> 
            <table class="table table-striped">
             <thead>
               <tr>
                <th>Image</th>
                <th>Id</th>
                <th>Value</th>
                <th>Suite</th>
                <th>Deck Id</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <tr v-for="card in cards">
            <td><img height="70" width="50" v-bind:src="card.path"></td>
            <td>{{ card.id }}</td>
            <td>{{ card.value }}</td>
            <td>{{ card.suite }}</td>
            <td>{{ card.deck_id }}</td>
            <td><a class="btn btn-xs btn-danger" v-on:click.prevent="deleteCard(card)">Delete</a></td>
        </tr>
    </tbody>
</table>
</div>
</div>
</template>

<script type="text/javascript">

import CardAdd from './cardAdd.vue';

export default {
    props: ['deck'],
    data: function(){
        return {
            cards:[],
            addingCard: false,
        }
    },
    methods: {
        getCards(deck){
            let config = {
                headers: {
                  'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
              }
          };
          axios.get('api/decks/'+deck.id+'/cards', config).then(response=>{
            this.cards = response.data; 
        }).catch(function(error){
            console.log(error);
        });
    },
    closeCardAdd(){
        this.addingCard = false;
    },
    openAddCard(){
        this.addingCard = true;
    },
    cancelEdit: function(){
        this.$emit('cancel-click');
    },
    deleteCard(card){
     let config = {
        headers: {
            'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
        }
    };
    axios.delete('api/cards/'+card.id, config).then(response=>{
        console.log(response.data);
        this.$emit('delete-card-click');
        this.getCards(this.deck);
    }).catch(function(error){
        console.log(error);
    });
},
updateDeckList(){
    this.$emit('complete-deck-update');
},
},
components: {
    'card-add': CardAdd
},
mounted(){
    this.getCards(this.deck);
},

}

</script>

<style scoped>  

</style>