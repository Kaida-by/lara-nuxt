<template>
  <div class="container">
    <p>Register</p>
    <form @submit.prevent="register">
      <label>Name: </label>
      <input v-model="form.name" type="text" name="name" :class="{ 'is-invalid': errors.name }" placeholder="name">
      <div class="invalid-feedback" v-if="errors.name">
        {{ errors.name[0] }}
      </div>
      <label>Email: </label>
      <input v-model="form.email" type="email" name="email" :class="{ 'is-invalid': errors.email }" placeholder="email">
      <div class="invalid-feedback" v-if="errors.email">
        {{ errors.email[0] }}
      </div>
      <label>Password: </label>
      <input v-model="form.password" type="password" name="password" :class="{ 'is-invalid': errors.password }" placeholder="password">
      <div class="invalid-feedback" v-if="errors.password">
        {{ errors.password[0] }}
      </div>
      <label>Password Confirmation: </label>
      <input v-model="form.password_confirmation" type="password" name="password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" placeholder="password confirmation">
      <div class="invalid-feedback" v-if="errors.password_confirmation">
        {{ errors.password_confirmation[0] }}
      </div>
      <input type="submit" value="Register">
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
  name: 'Register',
  middleware: 'guest',
  components: {
    SocialLogin
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      error: this.$route.query.error
    }
  },
  methods: {
    async register() {
      try {
        await this.$axios.post('/auth/register', this.form);
      } catch(e) {
        return;
      }
      this.$auth.login({data: this.form});

      this.$router.push({name: 'index'});
    }
  }
}
</script>
