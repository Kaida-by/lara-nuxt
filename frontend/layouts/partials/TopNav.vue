<template>
  <div class="container">
    <div class="logo">
      <nuxt-link to="/">JWT-Auth</nuxt-link>
    </div>
    <div class="home-div" v-if="$auth.loggedIn">
      <nuxt-link to="/dashboard">Dashboard</nuxt-link>
    </div>
    <template v-if="$auth.loggedIn">
      <div>
        <div>
          {{ $auth.user.name }}
        </div>
        <div @click.prevent="logOut">logout</div>
      </div>
    </template>
    <template v-else>
      <div class="auth-div">
        <nuxt-link to="/auth/login">Login</nuxt-link>
      </div>
      <div class="auth-div">
        <nuxt-link to="/auth/register">Register</nuxt-link>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: "TopNav",
  methods: {
    logOut() {
      this.$auth.logout();
    }
  }
}
</script>
