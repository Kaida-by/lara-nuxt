<template>
  <div class="container">
    <p>Sign-in</p>
    <form @submit.prevent="login">
      <label>Email: </label>
      <input v-model="form.email" type="email" name="email" :class="{ 'is-invalid': errors.text }" placeholder="email">
      <div class="invalid-feedback" v-if="errors.text">
        {{ errors.text[0] }}
      </div>
      <label>Password: </label>
      <input v-model="form.password" type="password" name="password" placeholder="password">

      <input type="submit" value="Sign-in">
    </form>

    <div v-if="error" class="err_r">
      {{ error }}
    </div>
    <social-login></social-login>
  </div>
</template>

<script>

import SocialLogin from "~/components/SocialLogin";

export default {
  name: "login",
  middleware: 'guest',
  components: {
    SocialLogin
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      error: this.$route.query.error
    }
  },
  methods: {
    async login() {
      try {
        await this.$auth.login({data: this.form});
      } catch(e) {
        return;
      }

      this.$router.push({name: 'index'});
    }
  }
}
</script>
