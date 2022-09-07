<template>
  <div class="social-login-block">
    <button @click="socialLogin('google')">
      google
    </button>
    <button @click="socialLogin('github')">
      github
    </button>
    <button @click="socialLogin('vkontakte')">
      vk
    </button>
  </div>
</template>

<script>
export default {
  name: "SocialLogin",
  methods: {
    socialLogin(service) {
      window.location.href = `https://api.zh-bel.tk/api/auth/login/${service}`;
    }
  }
}
</script>

<style scoped>

</style>
