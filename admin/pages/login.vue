<template>
  <div class="container mx-auto align-middle flex w-100">
    <div class="sm:mx-auto w-2/5 h-full flex flex-col items-center justify-center">
      <div class="bg-white w-full rounded-lg pt-12 pb-7">
        <h1 class="text-center w-full pb-5 text-2xl">Sign In</h1>
        <el-form :model="form" status-icon :rules="rules" ref="form" class="flex flex-col justify-center items-center">
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
      error: this.$route.query.error,
      rules: {
        email: [
          { required: true, message: 'Please input Activity email', trigger: 'blur' },
        ],
        password: [
          { required: true, message: 'Please input Activity password', trigger: 'blur' },
          { min: 8, max: 20, message: 'Length must be longer than 8 characters', trigger: 'blur' }
        ],
      }
    }
  },
  methods: {
    login(validData) {
      this.$refs[validData].validate((valid) => {
        if (valid) {
          this.$auth.login({data: this.form});
          $router.push({path: '/admin'});
        } else {
          return false;
        }
      })
    }
  }
}
</script>

<style scoped>
  .container {
    height: 100vh;
  }
</style>
