<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div v-if="vacancy" class="vacancy">

        <div class="cv_item">
          <div class="top_art_header">
            <h2>{{ vacancy.title }}</h2>
          </div>
          <div class="med_art_text" v-html="vacancy.description"></div>
          <div>+375{{ vacancy.phone.number }}</div>
        </div>

        <div class="profile">
          <el-row>
            <el-col class="h-profile">Автор:</el-col>
          </el-row>
          <div class="art_profile flex">
            <el-row v-if="vacancy.user.profile.images[0]">
              <el-col><img :src=vacancy.user.profile.images[0].src alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>
                {{ vacancy.user.profile.surname +
              ' ' +
              vacancy.user.profile.name +
              ' ' +
              vacancy.user.profile.patronymic }}
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
      vacancy: null,
      form: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/vacancy/' + this.$route.params.id)
          .then((res) => {
            this.vacancy = res.data
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