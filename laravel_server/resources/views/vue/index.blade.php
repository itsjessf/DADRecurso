@extends('master')

@section('title', 'Vue.js App')

@section('content')
      <router-link to="/loginUser">Login</router-link> -
      <router-link to="/logout">Logout</router-link> -
      <router-link to="/useraccount">Account Settings</router-link>-
      <router-link to="/statistics">Statistics</router-link> -
      <router-link to="/register">Register</router-link> -
      <router-link to="/multiblackjack">Play Black Jack!!</router-link>
      <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vueapp.js">
</script>
 @stop
