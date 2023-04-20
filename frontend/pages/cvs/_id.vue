<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div v-if="cv" class="cv">

        <div class="cv_item">
          <div class="top_art_header">
            <h2>{{ cv.title }}</h2>
          </div>
          <div class="med_art_text" v-html="cv.description"></div>
          <div>+375{{ cv.phone.number }}</div>
        </div>

        <div class="profile">
          <el-row>
            <el-col class="h-profile">Автор:</el-col>
          </el-row>
          <div class="art_profile flex">
            <el-row v-if="cv.user.profile.images[0]">
              <el-col><img :src=cv.user.profile.images[0].src alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>
                {{ cv.user.profile.surname +
              ' ' +
              cv.user.profile.name +
              ' ' +
              cv.user.profile.patronymic }}
              </el-col>
            </el-row>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "_id",
  data() {
    return {
      cv: null,
      form: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/cv/' + this.$route.params.id)
          .then((res) => {
            this.cv = res.data
          })
          .catch(err => console.log(err))
    }
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>

</style>