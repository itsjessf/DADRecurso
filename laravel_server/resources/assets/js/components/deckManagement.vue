    <template>
      <div>
        <div class="jumbotron">
          <h1>{{ title }}</h1>
        </div>
        <div>
          <button class="btn btn-xs btn-success" v-on:click.prevent="openDeckCreate()">Create a New Deck</button>
          <deck-create v-if="creating" :decks="decks" @create-deck-click = "createDeck" @cancel-click = "closeDeckCreate"></deck-create>
          <br><br>
        </div> 
        <deck-list :decks="decks" @activate-click="toggleActivateDeck" @delete-deck-click="deleteDeck" @delete-card-click="deleteCard"  @complete-deck-update="getDecks"></deck-list>
      </div>
    </template>

    <script type="text/javascript">

    import DeckList from './deckList.vue';
    import DeckCreate from './deckCreate.vue';

    export default {
      data: function(){
        return { 
          title: 'Deck Management',
          decks:[],
          creating: false,

        }
      },
      methods: {
        getDecks(){
          let config = {
            headers: {
              'Authorization': 'Bearer ' + window.localStorage.getItem("access_token_admin")
            }
          };
          axios.get('api/decks', config).then(response=>{
            this.decks = response.data.data; 
          }).catch(function(error){
            console.log(error);
          });
        },
        deleteDeck(deck){
          this.$emit('delete-deck-click');
          this.getDecks();
        },
        deleteCard(){
          this.getDecks();
        },
        toggleActivateDeck: function(deck){
          this.getDecks();
        },
        createDeck:function(deck){
          this.getDecks();
          this.creating = false;
        },
        openDeckCreate(){
          this.creating = true;
        },
        closeDeckCreate(){
          this.creating = false;
        },
      },
      components: {
        'deck-list': DeckList,
        'deck-create': DeckCreate
      },
      mounted(){
        this.getDecks();
      },


    }
    </script>

    <style scoped>  
    p {
      font-size: 2em;
      text-align: center;
    }
    </style>
