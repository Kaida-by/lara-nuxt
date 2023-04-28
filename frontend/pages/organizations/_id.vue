<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div v-if="organization" class="organization">

        <div class="cv_item">
          <div class="top_art_header">
            <h2>{{ organization.title }}</h2>
          </div>
          <div class="med_art_text" v-html="organization.description"></div>
          <div>+375{{ organization.phone.number }}</div>
        </div>

        <div class="profile">
          <el-row>
            <el-col class="h-profile">Автор:</el-col>
          </el-row>
          <div class="art_profile flex">
            <el-row v-if="organization.user.profile.images[0]">
              <el-col><img :src=organization.user.profile.images[0].src alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>
                {{ organization.user.profile.surname +
              ' ' +
              organization.user.profile.name +
              ' ' +
              organization.user.profile.patronymic }}
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
      organization: null,
      form: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/organization/' + this.$route.params.id)
          .then((res) => {
            this.organization = res.data
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