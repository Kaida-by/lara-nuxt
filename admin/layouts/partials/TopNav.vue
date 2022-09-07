<template>
  <div class="container header-container">
    <div class="header_flex">
      <div class="logo">
<!--        <nuxt-link to="/">Admin</nuxt-link>-->
      </div>
      <div class="header-sdk" v-if="$auth.loggedIn">
        <div>
          {{ $auth.user.name }}
        </div>
        <div @click.prevent="logOut">logout</div>
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
