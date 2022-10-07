<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div class="article">
        <div class="profile">
          <el-row>
            <el-col class="h-profile">Profile:</el-col>
          </el-row>
          <div class="art_profile flex">
            <el-row>
              <el-col><img src="#" alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>First name</el-col>
              <el-col>{{ article?.user?.profile?.name }}</el-col>
            </el-row>
            <el-row>
              <el-col>Surname</el-col>
              <el-col>{{ article?.user?.profile?.surname }}</el-col>
            </el-row>
            <el-row>
              <el-col>Patronymic</el-col>
              <el-col>{{ article?.user?.profile?.patronymic }}</el-col>
            </el-row>
          </div>
        </div>
        <div class="article_item">
          <el-row>
            <span>Title: </span>
            <span>{{ article.title }}</span>
          </el-row>
          <el-row>
            <span>Description: </span>
            <span>{{ article.description }}</span>
          </el-row>
          <el-row>
            <span>Images: </span>
            <div class="images_array flex gap-2.5">
              <div class="image_item" v-for="image in article.images">
                <el-image
                  style="width: 200px; height: 200px"
                  :src=image?.src alt="img"
                  :preview-src-list="[image?.src]" fit=none>
                </el-image>
              </div>
            </div>
          </el-row>
        </div>
        <div class="is_active_article">
          <form @submit.prevent="update">
            <div class="approve">
              <div>Approve?</div>
              <label class="switch">
                <input type="checkbox" v-if="article.status_id === 1" checked v-model="form.checked">
                <input type="checkbox" v-else v-model="form.checked">
                <div class="slider round"></div>
              </label>
            </div>
            <div class="bnt_sv">
              <input type="submit" value="Save">
            </div>
          </form>

          <div v-if="error" class="err_r">
            {{ error }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "id",
  data() {
    return {
      article: {},
      form: {},
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
    content:'NO';
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
    content:'YES';
  }
  .profile {
    border-bottom: 1px solid gray;
    padding: 10px;
  }
  .container {
    border-right: 1px solid gray;
    border-left: 1px solid gray;
  }
  .h-profile {
    font-size: 24px;
  }
  .art_profile .el-row {
    width: 190px;
  }
  .el-image {
    border: 1px solid gray;
  }
  .article_item {
    padding: 10px;
    border-bottom: 1px solid gray;
  }
  .is_active_article {
    padding: 10px;
  }
  .bnt_sv {
    display: flex;
    justify-content: end;
  }
  .bnt_sv input {
    width: fit-content;
    padding: 8px 24px;
    cursor: pointer;
    border-radius: 24px;
    transition: background-color 0.3s;
    background-color: #11bb0e;
  }
  .bnt_sv input:hover {
    transition: background-color 0.3s;
    background-color: #18d915;
  }
</style>
