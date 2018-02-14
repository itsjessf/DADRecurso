<template>
  <div>
   <table class="table table-striped">
     <thead>
       <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Nickname</th>
        <th>Email</th>
        <th>Blocked</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
     <tr v-for="user in users"  :key="user.id" :class="{activerow: blockingUser === user}">
       <td>{{ user.id }}</td>
       <td>{{ user.name }}</td>
       <td>{{ user.nickname }}</td>
       <td>{{ user.email }}</td>
       <td>{{ user.blocked? "Yes" : "No" }}</td>
       <td>
        <template v-if= "user.blocked == 0">
          <a class="btn btn-xs btn-danger" v-on:click.prevent="openUserBlock(user)">Block</a>
        </template>
        <template v-else>
          <a class="btn btn-xs btn-primary" v-on:click.prevent="openUserBlock(user)">Reactivate</a>
        </template>
        <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
      </td>
    </tr>
  </tbody>
</table>
<div> 
  <user-block v-if="blockingUser" :user="user" @block-saved="toggleBlockUser" @block-cancel="cancelEdit" ></user-block>
</div>
</div>
</template>

<script type="text/javascript">

import UserBlock from './userBlock.vue';

export default {
  props: ['users'],
  data: function(){
   return {
    user:null,
    blockingUser: null,
  }
},
methods: {

  deleteUser: function(user){
    this.$emit('delete-click', user);
  },
  cancelEdit: function(user){
    this.blockingUser = false;
  },
  openUserBlock: function(user){
    this.user = user;
    this.blockingUser = true;
  },
  toggleBlockUser: function(user){
    this.$emit('toggle-block-user', user);
  },
},
components: {
  'user-block': UserBlock
},
}
</script>

<style scoped>
tr.activerow {
  background: #123456  !important;
  color: #fff          !important;
}

</style>
