/*jshint esversion: 6 */

class MemoryGame {
    constructor(game, owner) {
        this.gameID = game.id;
        this.deckID = game.deck_used;
        this.gameEnded = false;
        this.gameStarted = false;
        this.gameOwner= owner.id;
        this.shuffledDeck = [];
        this.plays =[];
        this.currentPlay = [];
        this.playEnded = false;
        this.players = [];
        this.team1_cardpoints = 0;
        this.team2_cardpoints = 0;
        this.team_winner = 0;
        this.team_desconfiou = 0;
        this.team_renunciou = 0;
        this.team1_points = 0;
        this.team2_points = 0;
        this.trumpCard = {};
        this.trumpCardHidden = false;
        this.currentPlayerIndex = 0;
        this.question = false;
        this.currentTeam = 1;
        this.join(owner);

    }

    join(player){
        if(this.hasPlayer(player.id) || this.players.length == 4){
         return;            
     }        
     this.players.push(player);
     this.players[this.players.length-1].team = this.currentTeam;
     if(this.currentTeam == 1){
        this.currentTeam = 2;
        return;
    }
    this.currentTeam = 1;
}

startGame(cards){
    if(this.players.length < 4){
        return;
    }
    let deck = cards;
    this.shuffledDeck = this.shuffleDeck(deck);

    for(let i=0; i < this.players.length; i++){
        this.players[i].cards = [];
        this.players[i].total_points = 0;
        this.players[i].points = 0;  
    }
    this.gameStarted = true;
    //determinar o primeiro jogador a ter jogo
    this.currentPlayerIndex = this.getRandomPlayerIndex();
}

getRandomPlayerIndex(){
    return Math.floor(Math.random()*this.players.length);
}

isObjectEmpty(obj){
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}

dealCards(){
    let playerHand = [];
    for(let i=0; i < 10 ; i++){
        playerHand.push(this.shuffledDeck[i]);
    }
    this.shuffledDeck.splice(0, 10);
    //adiciona a nova carta ao array cards do jogador     
    this.players[this.currentPlayerIndex].cards = playerHand;
    if(this.isObjectEmpty(this.trumpCard)){
        this.trumpCard = this.players[this.currentPlayerIndex].cards[0];
    }
    this.nextPlayer();
    if(this.isObjectEmpty(this.shuffledDeck)){
        //garante que o primeiro a jogar é o jogador à esquerda de quem teve jogo primeiramente
        this.nextPlayer();
    }
}

nextPlayer(){
    if((this.currentPlayerIndex+1) == this.players.length){
        this.currentPlayerIndex = 0;
        return;
    }
    this.currentPlayerIndex++;
}

shuffleDeck(deck){
    let arraySize = deck.length;
    while(arraySize > 0){
        let index = Math.floor(Math.random()* arraySize);
        arraySize --;
        let temp = deck[arraySize];
        deck[arraySize] = deck[index];
        deck[index] = temp;
    }
    return deck;
}


getPlayerIndex(player){
 for(let i = 0; i < this.players.length; i++){
    if(this.players[i].id == player.id){
     return i;
 }
}   
}

getPlayer(userID){
    for(let i = 0; i < this.players.length; i++){
        if(this.players[i].id == userID){
            let player = this.players[i];
            return player;
        }
    }
}

play(player, card){
    if (!this.gameStarted || this.gameEnded) {
        return false;
    }
    if(!this.isObjectEmpty(this.trumpCard)){
        if(this.trumpCard.id == card.id){
            this.trumpCardHidden = true;
        }
    }
    card.id_player = player.id;
    this.currentPlay.push(card);
    //remove a carta do array de cartas do jogador
    this.players[this.currentPlayerIndex].cards.splice(this.getCardIndex(player,card),1);
    this.nextPlayer()
    this.checkPlayEnded();
}

checkPlayEnded(){
    if(this.currentPlay.length == 4){
        this.playEnded = true;
        this.calculatePlayWinner();
        return true;
    }
    return false;
}

calculatePlayWinner(){
    let trumpCards = [];
    let firstSuiteCards = [];    
    for(let i=0; i< this.currentPlay.length; i++){
        if(this.currentPlay[i].suite == this.trumpCard.suite){
            trumpCards.push(this.currentPlay[i]);
        }
    }
    if(!this.isObjectEmpty(trumpCards) ){
        this.calculatePlayWinnerTeam(trumpCards);
    }else{
        for(let i=0; i< this.currentPlay.length; i++){
            if(this.currentPlay[i].suite == this.currentPlay[0].suite){
                firstSuiteCards.push(this.currentPlay[i]);
            }
        }
        this.calculatePlayWinnerTeam(firstSuiteCards);
    }
}

calculatePlayWinnerTeam(playCards){
    let winnerTeam = "";
    let winnerPlayer = [];
    let values =[];
    for(let x = 0; x< playCards.length;x++){
        values.push(this.getCardPoints(playCards[x].value));
    }
    values.sort(function(a, b){return a-b});
    for(let y =0; y<playCards.length; y++){
        if((this.getCardPoints(playCards[y].value)) == values[values.length-1]){
            winnerPlayer = this.getPlayer(playCards[y].id_player);
            this.currentPlayerIndex = this.getPlayerIndex(winnerPlayer);
            winnerTeam = winnerPlayer.team;
            this.currentPlay.team= winnerTeam;
            this.plays.push(this.currentPlay);
            console.log(this.plays);
            this.currentPlay = [];
            return;
        }
    }
}

checkGameEnded(){
    if(this.gameEnded){
        return true;
    }
    if(this.plays.length == 10){
        console.log("Game ended");
        let team1Cards = [];
        let team2Cards = [];
        for(let i=0; i<this.plays.length; i++){
            if(this.plays[i].team == 1){
                team1Cards.push(this.plays[i]);
            }else{
                team2Cards.push(this.plays[i]);
            }
        }
        for(let x = 0; x < team1Cards.length; x++){
            for(let y = 0; y < team1Cards[x].length; y++){
                this.team1_cardpoints += this.getCardPoints(team1Cards[x][y].value);
            }
        } 
        for(let y = 0; y < team2Cards.length; y++){
            for(let x = 0; x < team2Cards[y].length; x++){
                this.team2_cardpoints += this.getCardPoints(team2Cards[y][x].value);
            }
        } 
        this.calculateScore();
        this.gameEnded = true;
        return true;    
    }
    return false;

}


calculateScore(){
    let highestScore = Math.max(this.team1_cardpoints, this.team2_cardpoints);
    if(this.team1_cardpoints == this.team2_cardpoints){
        console.log("Empate");
        return;
    }
    if(highestScore == this.team1_cardpoints){
        this.team_winner = 1;
        this.team1_points = this.calculatePoints(this.team1_cardpoints);
        console.log("Team 1 winner");
    }else{
        this.team_winner =2;
        this.team2_points = this.calculatePoints(this.team2_cardpoints);
        console.log("Team 2 winner");
    }
    console.log("team 1 points" + this.team1_points);
    console.log("team 2 points" + this.team2_points);



}

calculatePoints(score){
    if(score >= 61 && score <=90){
        return 1;
    }
    if(score >= 91 && score <=119){
        return 2;
    }
    if(score == 120){
        return 4;
    }

    return 0;
}

getObjectEndGame(){
 return {
    team_renunciou: this.team_renunciou,
    team_desconfiou: this.team_desconfiou,
    team1_points: this.team1_points,
    team2_points: this.team2_points,
    team1_cardpoints: this.team1_cardpoints,
    team2_cardpoints: this.team2_cardpoints,
    team_winner: this.team_winner
};
}



getCardIndex(player,card){
    let cardIndex = null;
    let playerIndex = this.getPlayerIndex(player);
    for(let i=0; i<this.players[playerIndex].cards.length; i++){
        if(this.players[playerIndex].cards[i].id == card.id){
            cardIndex = i;
        }

    }
    return cardIndex;
}

hasPlayer(playerId){
    for(let i=0; i<this.players.length;i++){
        if(this.players[i].id == playerId){
            return true;
        }
    }
}

questionPlay(player){
    console.log("--- Checking question");
    this.gameEnded = true;
    this.team_desconfiou = player.team;
    let questionedPlayers = [];
    for(let i=0; i<this.players.length; i++){
        if(this.players[i].team != this.team_desconfiou){
            questionedPlayers.push(this.players[i]);
        }
    }
    this.team_renunciou = questionedPlayers[0].team;
    for(let indexJogada = 0; indexJogada<this.plays.length; indexJogada++){
        let questionedSuite = this.plays[indexJogada][0].suite;
        for(let x = 0; x < questionedPlayers.length; x++){
            let assistiu = false;

            //VERIFICAR SE ASSISTIU AO SUIT
            for(let cardIndex = 0; cardIndex <this.plays[indexJogada].length; cardIndex++){

                if(this.plays[indexJogada][cardIndex].id_player == questionedPlayers[x].id
                    && questionedSuite == this.plays[indexJogada][cardIndex].suite)
                {
                    assistiu = true;
                    break;
                }
            }
            if(assistiu){
                continue;
            }    
            //OBTER CARTAS DISPONIVEIS NA JOGADA A ANALISAR
            console.log("Obter cartas disponiveis")
            let availableCards = this.getAvailableCards(questionedPlayers[x], indexJogada);
            console.log(availableCards);
            for(let y = 0; y<availableCards.length; y++){
                if(availableCards[y].suite == questionedSuite){
                    console.log("Renuncia");
                    if(questionedPlayers[x].team == 1){
                        this.team2_points  += 4;
                        this.team1_points  -= 4;
                        this.team_winner = 2;

                    }else{
                        this.team1_points  += 4;
                        this.team2_points  -= 4;
                        this.team_winner = 1;
                    }
                    console.log("Game has ended, busted");
                    return "Game has ended, busted"
                }
            }
        }
    }

    console.log("False claim");
    if(this.team_desconfiou == 1){
        this.team1_points -= 4;
        this.team2_points += 4;
        this.team_winner = 2;

    } else {
        this.team1_points += 4;
        this.team2_points -= 4;
        this.team_winner = 1;

    }
    return "Game has ended, false claim!"
}

getAvailableCards(player, indexJogada){

    let cards = player.cards;
    for(let i = indexJogada; i < this.plays.length; i++){
        for(let cardIndex = 0; cardIndex <this.plays[i].length; cardIndex++){
            if(this.plays[i][cardIndex].id_player == player.id){
                cards.push(this.plays[i][cardIndex]);
            }  
        }
        return cards;
    }

}
//leave(playerId){
  //  if(!this.hasPlayer(playerId)){
  //      return;            
  //  }
  //  let index = this.players.indexOf(playerId);
  //  this.players.splice(index,1);
  //  this.cards.delete(playerID);
  //  this.numPlayers--;

//}
getCardPoints(cardValue){
    switch(cardValue){
        case 'Ace' :
        return 11;
        break;
        case '7' :
        return 10;
        break;
        case 'Jack' :
        return 3;
        break;
        case 'Queen' :
        return 2;
        break;
        case 'King' :
        return 4;
        default:
        return 0;
        break;
    }
}



}

module.exports = MemoryGame;
