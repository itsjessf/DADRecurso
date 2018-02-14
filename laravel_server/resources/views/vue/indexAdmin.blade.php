@extends('master')

@section('title', 'Vue.js App')

@section('content') 

<router-link to="/loginAdmin">Login</router-link> -
<router-link to="/logout">Logout</router-link> -
<router-link to="/adminAccount">Account</router-link> -
<router-link to="/statistics">Statistics</router-link> -
<router-link to="/userManagement">User Management</router-link> - 
<router-link to="/gameManagement">Game Management</router-link>

<router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vueadmin.js"></script>

<!-- <script src="/js/manifest.js"></script>
<script src="/js/vendor.js"></script>
<script src="/js/vueapp.js"></script>
-->
@stop
