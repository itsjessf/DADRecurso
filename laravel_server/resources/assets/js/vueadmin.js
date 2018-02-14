/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue');

 import VueRouter from 'vue-router';
 import VueSocketio from 'vue-socket.io';

 Vue.use(VueRouter);

 Vue.use(VueSocketio, 'http://192.168.10.10:8080');

 const userManagement = Vue.component('userManagement', require('./components/user.vue'));
 const loginAdmin = Vue.component('loginAdmin', require('./components/loginAdmin.vue'));
 const statistics = Vue.component('statistics', require('./components/adminStatistics.vue'));
 const logout = Vue.component('logout', require('./components/adminLogout.vue'));
 const adminAccount = Vue.component('adminAccount', require('./components/adminAccount.vue'));
 const gameManagement = Vue.component('gameManagement', require('./components/deckManagement.vue'));
 const multiplayerGame = Vue.component('multiplayergame', require('./components/blackjack.vue'));

 const routes = [
 { path: '/loginAdmin', component:loginAdmin },
 { path: '/statistics', component:statistics },
 { path: '/logout', component:logout },
 { path: '/userManagement', component: userManagement },
 { path: '/adminAccount', component: adminAccount },
 { path: '/gameManagement', component: gameManagement },
 ];

 const router = new VueRouter({
  routes:routes
});

 const app = new Vue({
  router,
  data:{
  }
}).$mount('#app');
