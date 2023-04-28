<template>
  <div class="container header-container">
    <div class="header_flex">
      <div class="logo">
        <nuxt-link to="/">Home</nuxt-link>
      </div>
      <div class="header-sdk" v-if="$auth.loggedIn">
        <div>
          {{ $auth.user.name }}
        </div>
        <div class="auth-div">
          <nuxt-link to="/personal-cabinet">personal-cabinet</nuxt-link>
        </div>
        <Notifications></Notifications>
        <div @click.prevent="logOut">logout</div>
      </div>
      <div class="header-sdk" v-else>
        <div class="auth-div">
          <nuxt-link to="/auth/login">Login</nuxt-link>
        </div>
        <div class="auth-div">
          <nuxt-link to="/auth/register">Register</nuxt-link>
        </div>
      </div>
    </div>
    <div class="bot_header_flex">
      <div class="bot_header_in">
        <nuxt-link to="/articles">Articles</nuxt-link>
      </div>
      <div class="bot_header_in">
        <nuxt-link to="/posters">Posters</nuxt-link>
      </div>
      <div class="bot_header_in">
        <nuxt-link to="/cvs">CVs</nuxt-link>
      </div>
      <div class="bot_header_in">
        <nuxt-link to="/vacancies">Vacancies</nuxt-link>
      </div>
      <div class="bot_header_in">
        <nuxt-link to="/organizations">Organizations</nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>

import Notifications from "~/layouts/partials/Notifications";

export default {
  name: "TopNav",
  components: {Notifications},
  methods: {
    logOut() {
      if (this.$auth.loggedIn) {
        this.$auth.logout();
      }
    },
  }
}
</script>

<style scoped>
  .header-sdk {
    display: flex;
    flex-direction: column;
  }
  .header-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: 0 auto;
  }
  .header_flex {
    display: flex;
    justify-content: space-between;
  }
  .bot_header_flex {
    display: flex;
    column-gap: 10px;
    background-color: #b4c1d9;
  }
</style>
