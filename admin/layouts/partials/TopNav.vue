<template>
  <div class="container header-container mx-auto align-middle flex w-100 mb-6">
    <div class="header_flex">
      <div class="logo">
        Logo
      </div>
      <div class="header-sdk" v-if="$auth.loggedIn">
        <div class="py-2">
          Hello, {{ $auth.user.name }}
        </div>
        <el-button type="info" plain @click.prevent="logOut">logout</el-button>
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
