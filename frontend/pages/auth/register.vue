<template>
<!--  <div class="container">-->
<!--    <p>Register</p>-->
<!--    <form @submit.prevent="register">-->
<!--      <label>Name: </label>-->
<!--      <input v-model="form.name" type="text" name="name" :class="{ 'is-invalid': errors.name }" placeholder="name">-->
<!--      <div class="invalid-feedback" v-if="errors.name">-->
<!--        {{ errors.name[0] }}-->
<!--      </div>-->
<!--      <label>Email: </label>-->
<!--      <input v-model="form.email" type="email" name="email" :class="{ 'is-invalid': errors.email }" placeholder="email">-->
<!--      <div class="invalid-feedback" v-if="errors.email">-->
<!--        {{ errors.email[0] }}-->
<!--      </div>-->
<!--      <label>Password: </label>-->
<!--      <input v-model="form.password" type="password" name="password" :class="{ 'is-invalid': errors.password }" placeholder="password">-->
<!--      <div class="invalid-feedback" v-if="errors.password">-->
<!--        {{ errors.password[0] }}-->
<!--      </div>-->
<!--      <label>Password Confirmation: </label>-->
<!--      <input v-model="form.password_confirmation" type="password" name="password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" placeholder="password confirmation">-->
<!--      <div class="invalid-feedback" v-if="errors.password_confirmation">-->
<!--        {{ errors.password_confirmation[0] }}-->
<!--      </div>-->
<!--      <input type="submit" value="Register">-->
<!--    </form>-->

<!--    <div v-if="error" class="err_r">-->
<!--      {{ error }}-->
<!--    </div>-->
<!--    <social-login></social-login>-->
<!--  </div>-->

  <div class="container mx-auto align-middle flex w-100">
    <div class="sm:mx-auto w-2/5 h-full flex flex-col items-center justify-center">
      <div class="bg-white w-full rounded-lg pt-12 pb-7">
        <h1 class="text-center w-full pb-5 text-2xl">Registration</h1>
        <el-form :model="form" status-icon :rules="rules" ref="form" class="flex flex-col justify-center items-center">
          <el-form-item prop="name" class="w-3/5">
            <el-input placeholder="Name" type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }"></el-input>
          </el-form-item>

          <el-form-item prop="email" class="w-3/5">
            <el-input placeholder="Email" type="email" v-model="form.email" :class="{ 'is-invalid': errors.name }"></el-input>
          </el-form-item>

          <el-form-item prop="password" class="w-3/5">
            <el-input placeholder="Password" type="password" v-model="form.password" :class="{ 'is-invalid': errors.name }"></el-input>
          </el-form-item>

          <el-form-item prop="password_confirmation" class="w-3/5">
            <el-input placeholder="Password Confirmation" type="password" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.name }"></el-input>
          </el-form-item>

          <el-form-item class="mb-0 text-center w-3/5">
            <el-button type="primary" @click="register('form')" class="px-6 w-full">
              Register
            </el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
<!--    <social-login></social-login>-->
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

<style scoped>
.container {
  margin: 5% auto 0 auto;
}
.is-invalid .el-input__inner {
  border-color: red;
}
</style>
