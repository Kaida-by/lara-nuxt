<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div v-if="poster" class="poster">

        <div class="poster_item">
          <div class="top_art_header">
            <h2>{{ poster.title }}</h2>
          </div>
          <div v-if="poster.images[0]" class="top_art_img">
            <el-image :src="poster.images[0].src" alt="" :preview-src-list="[poster.images[0].src]" fit=cover>
            </el-image>
          </div>
          <div class="med_art_text" v-html="poster.description"></div>

          <div class="bot_art_images">
            <div class="bot_art_image_in" v-for="image in poster.images">
              <el-image :src=image.src alt="img" :preview-src-list="[image.src]" fit=cover>
              </el-image>
            </div>
          </div>
        </div>

        <div class="profile">
          <el-row>
            <el-col class="h-profile">Автор:</el-col>
          </el-row>
          <div class="art_profile flex">
            <el-row v-if="poster.user.profile.images[0]">
              <el-col><img :src=poster.user.profile.images[0].src alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>
                {{ poster.user.profile.surname +
              ' ' +
              poster.user.profile.name +
              ' ' +
              poster.user.profile.patronymic }}
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
  // name: "_id",
  data() {
    return {
      poster: null,
      form: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/poster/' + this.$route.params.id)
        .then((res) => {
          this.poster = res.data.data[0]
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
  .preview {
    display: flex;
  }
  .preview > div {
    display: flex;
  }
  .img {
    width: 200px;
    height: 200px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  .image_i {
    position: relative;
    height: 100%;
    width: 100%;
    object-fit: cover;
  }
  .img span {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: white;
    font-family: sans-serif;
    padding: 0 0 0 2px;
    font-size: 12px;
    cursor: pointer;
  }
</style>
