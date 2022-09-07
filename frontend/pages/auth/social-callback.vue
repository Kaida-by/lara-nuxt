<template>
  <div class="container">
    <div class="loader"></div>
    <p>Please wait while we're logging you in...</p>
  </div>
</template>

<script>
export default {
  name: "social-callback",
  middleware: 'guest',
  data() {
    return {
      token: this.$route.query.token ? this.$route.query.token : null
    }
  },
  mounted() {
    // this.$auth.login({
    //   data: {
    //     token: this.token
    //   }
    // }).catch( (e) => {
    //   return this.$router.push('/auth/register?error=Your token appeared to be invalid, please try again.')
    // })

    this.$auth.setStrategy('local');
    this.$auth.setUserToken('Bearer ' + this.token, 'Bearer ' + this.token);
    this.$auth.fetchUser().then(() => {
      return this.$router.push('/');
    }).catch((e) => {
      this.$auth.logout();

      return this.$router.push(`/auth/${this.$route.query.origin ? this.$route.query.origin : 'register'}?error=Your token appeared to be invalid, please try again.`);
    })
  }
}
</script>

<style scoped>

</style>
