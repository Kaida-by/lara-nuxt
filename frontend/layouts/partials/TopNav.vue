<template>
  <div class="container header-container">
    <div class="header_flex">
      <div class="logo">
        <nuxt-link to="/">JWT-Auth</nuxt-link>
      </div>
      <div class="header-sdk" v-if="$auth.loggedIn">
        <div>
          {{ $auth.user.name }}
        </div>
        <div class="auth-div">
          <nuxt-link to="/personal-cabinet">personal-cabinet</nuxt-link>
        </div>
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
      <div>
        <div>
          <nuxt-link to="/articles">Articles</nuxt-link>
        </div>
      </div>
    </div>
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

<style scoped>
  .header-sdk {
    display: flex;
    flex-direction: column;
  }
  .header-container {
    display: flex;
    flex-direction: column;
  }
  .header_flex {
    display: flex;
    justify-content: space-between;
  }
</style>
