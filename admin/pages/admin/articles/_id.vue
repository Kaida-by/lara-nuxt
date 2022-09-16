<template>
  <div class="container mx-auto w-100">
    <div class="article">
      <div class="art_profile">
        <el-row>
          <el-col>Profile:</el-col>
        </el-row>
        <el-row>
          <el-col>avatar</el-col>
          <el-col>img</el-col>
        </el-row>
<!--        <el-row>-->
<!--          <el-col>First name</el-col>-->
<!--          <el-col>{{ article.user.profile.name }}</el-col>-->
<!--        </el-row>-->
<!--        <el-row>-->
<!--          <el-col>Surname</el-col>-->
<!--          <el-col>{{ article.user.profile.surname }}</el-col>-->
<!--        </el-row>-->
<!--        <el-row>-->
<!--          <el-col>Patronymic</el-col>-->
<!--          <el-col>{{ article.user.profile.patronymic }}</el-col>-->
<!--        </el-row>-->
      </div>
      <h2>{{ article.title }}</h2>
      <p>{{ article.description }}</p>
    </div>
    <div class="is_active_article">
      <form @submit.prevent="update">
        <div>Approve?</div>
        <label class="switch">
          <input type="checkbox" v-if="article.status_id === 1" checked v-model="form.checked">
          <input type="checkbox" v-else v-model="form.checked">
          <div class="slider round"></div>
        </label>
        <div>
          <input type="submit" value="Save">
        </div>
      </form>

      <div v-if="error" class="err_r">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "_id",
  data() {
    return {
      article: [],
      form: {
        // checked: false,
      },
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/admin/article/edit/' + this.$route.params.id)
        .then((res) => {
          this.article = res.data.data[0]
        })
        .catch(err => console.log(err))
    },
    async update() {
      try {
        await this.$axios.patch('/admin/article/approve/' + this.$route.params.id, this.form);
      } catch(e) {
        return;
      }

      // this.$router.push({name: 'admin'});
    }
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>
  /*toggle checkbox*/
  .switch {
    position: relative;
    display: inline-block;
    width: 90px;
    height: 34px;
  }
  .switch input {display:none;}
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ca2222;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 34px;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
  }
  input:checked + .slider {
    background-color: #2ab934;
  }
  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }
  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(55px);
  }
  .slider:after
  {
    content:'OFF';
    color: white;
    display: block;
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    font-size: 10px;
    font-family: Verdana, sans-serif;
  }
  input:checked + .slider:after
  {
    content:'ON';
  }
  /*toggle checkbox*/
</style>
