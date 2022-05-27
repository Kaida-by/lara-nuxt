<template>
  <div class="container">
    <p>Sign-in</p>
    <form @submit.prevent="login">
      <label>Email: </label>
      <input v-model="form.email" type="email" name="email" :class="{ 'is-invalid': errors.email }" placeholder="email">
      <div class="invalid-feedback" v-if="errors.email">
        {{ errors.email[0] }}
      </div>
      <label>Password: </label>
      <input v-model="form.password" type="password" name="password" placeholder="password">

      <input type="submit" value="Sign-in">
    </form>
  </div>
</template>

<script>
export default {
  name: "login",
  middleware: 'guest',
  data() {
    return {
      form: {
        email: '',
        password: '',
      }
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

