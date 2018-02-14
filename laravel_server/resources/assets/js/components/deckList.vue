<template>
  <div> 
    <table class="table table-striped">
     <thead>
       <tr>
        <th>Card Back</th>
        <th>Id</th>
        <th>Name</th>
        <th>Active</th>
        <th>Complete</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
      </tr>
      <tr v-for="deck in decks">
        <td><img height="75" width="50" v-bind:src="deck.hidden_face_image_path"></td>
        <td>{{ deck.id }}</td>
        <td>{{ deck.name }}</td>
        <td>{{ deck.active? "Yes":"No"}}</td>
        <td>{{ deck.complete? "Yes":"No"}}</td>
        <template v-if= "deck.active == 1">
          <a class="btn btn-xs btn-danger" v-on:click.prevent="toggleActivateDeck(deck)">Deactivate</a>
        </template>
        <template v-else>
          <a class="btn btn-xs btn-primary" v-on:click.prevent="toggleActivateDeck(deck)">Activate</a>
        </template>
        <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteDeck(deck)">Delete</a>
        <a class="btn btn-xs btn-info" v-on:click.prevent="manageDeck(deck)">Manage</a>
      </tr>
    </tbody>
  </table>
  <card-edit :deck="managingDeck" v-if="managing" @delete-card-click="deleteCard" @complete-deck-update="updateDeckList" ></card-edit>
</div>
</template>

<script type="text/javascript">

import CardEdit from './cardEdit.vue';


export default {
  props: ['decks'],
  data: function(){
    return {
      managing:false,
      managingDeck : null,
    }
  },
  methods: {
    toggleActivateDeck: function(deck){
      let config = {
        headers: {
          'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
        }
      };
      axios.post('api/decks/'+deck.id+'/activate', {}, config)
      .then(response => {
        this.$emit('activate-click', deck);
      }).catch(function(error){
        console.log(error);
      });
    },
    manageDeck(deck){
      this.managing = true;
      this.managingDeck = deck;
    },
    deleteDeck(deck){
      let config = {
        headers: {
          'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
        }
      };
      axios.delete('api/decks/'+deck.id, config).then(response=>{
        this.managing = false;
        this.$emit('delete-deck-click', deck);
      }).catch(function(error){
        console.log(error);
      });
    },
    deleteCard(){
      this.$emit('delete-card-click');

    },
    updateDeckList(){
      this.$emit('complete-deck-update');
    },
  },
  components: {
    'card-edit': CardEdit
  },
}
</script>

<style scoped>
tr.activerow {
  background: #123456  !important;
  color: #fff          !important;
}

</style>
