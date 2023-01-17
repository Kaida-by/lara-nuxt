<template>
  <div class="container mx-auto align-middle flex w-100">
    <div class="sm:mx-auto w-2/5 h-full flex flex-col items-center justify-center">
      <div class="bg-white w-full rounded-lg pt-12 pb-7">
        <h1 class="text-center w-full pb-5 text-2xl">Sign In</h1>
        <el-form :model="form" ref="form" class="flex flex-col justify-center items-center">
          <el-form-item prop="email" class="w-3/5">
            <el-input placeholder="Email" type="email" v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item prop="password" class="w-3/5">
            <el-input placeholder="Password" type="password" v-model="form.password"></el-input>
          </el-form-item>
          <el-form-item class="mb-0 text-center w-3/5">
            <el-button type="primary" @click="login('form')" class="px-6 w-full">
              Login
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
        this.connectToPrivateChannel();
        await this.$axios.get('/get-notifications')
          .then((res) => {
            this.notifications = res.data.data
          })
          .catch(err => console.log(err))
      } catch(e) {
        return;
      }

      this.$router.push({name: 'index'});
    },
    connectToPrivateChannel() {
      window.Echo.private(`user.1`)
        .listen('user.1', ({article}) => {
          console.log(article)
        })
    }
  }
}
</script>

<style scoped>
.container {
  margin: 5% auto 0 auto;
}
</style>
