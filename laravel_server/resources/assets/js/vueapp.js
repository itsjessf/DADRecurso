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

//const user = Vue.component('user', require('./components/user.vue'));
const loginUser = Vue.component('loginUser', require('./components/loginUser.vue'));
const statistics = Vue.component('statistics', require('./components/statistics.vue'));
const logout = Vue.component('logout', require('./components/logout.vue'));
const register = Vue.component('register', require('./components/register.vue'));
const multiplayerGame = Vue.component('multiplayergame', require('./components/blackjack.vue'));
const useraccount = Vue.component('useraccount', require('./components/userAccount.vue'));


const routes = [
{ path: '/', redirect: '/statistics' },
{ path: '/statistics', component:statistics },
{ path: '/loginUser', component:loginUser },
{ path: '/logout', component:logout },
{ path: '/register', component:register },
{ path: '/multiblackjack', component: multiplayerGame },
{ path: '/useraccount', component: useraccount}
];

const router = new VueRouter({
  routes:routes
});

const app = new Vue({
  router,
  data:{
  }
}).$mount('#app');
