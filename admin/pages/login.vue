<template>
  <div className="container">
    <p>Sign-in</p>
    <form @submit.prevent="login" :class="{ 'is-invalid': errors.text }">
      <label>Email: </label>
      <input v-model="form.email" type="email" name="email" placeholder="email">

      <label>Password: </label>
      <input v-model="form.password" type="password" name="password" placeholder="password">

      <input type="submit" value="Sign-in">
      <div className="invalid-feedback" v-if="errors.text">
        {{ errors.text[0] }}
      </div>
    </form>
  </div>
</template>

<script>

export default {
  name: "login",
  auth: 'guest',

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
        // await this.$axios.post('/admin/login', this.form);
        await this.$auth.login({data: this.form});
      } catch (e) {
        return;
      }

      this.$router.push({path: '/admin'});
    }
  }
}
</script>
