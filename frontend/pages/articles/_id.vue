<template>
  <div class="bg-white">
    <div class="container mx-auto w-100">
      <div v-if="article" class="article">

        <div class="article_item">
          <div class="top_art_header">
            <h2>{{ article.title }}</h2>
          </div>
          <div v-if="article.images[0].src" class="top_art_img">
            <el-image :src="article.images[0].src" alt="" :preview-src-list="[article.images[0].src]" fit=cover>
            </el-image>
          </div>
          <div class="med_art_text">
            {{ article.description }}
          </div>
          <div class="bot_art_images">
            <div class="bot_art_image_in" v-for="image in article.images">
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
            <el-row>
              <el-col><img :src=article.user.profile.images[0].src alt="img"></el-col>
            </el-row>
            <el-row>
              <el-col>
                {{ article.user.profile.surname +
                ' ' +
                article.user.profile.name +
                ' ' +
                article.user.profile.patronymic }}
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
  name: "id",
  data() {
    return {
      article: null,
      form: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/article/' + this.$route.params.id)
        .then((res) => {
          this.article = res.data.data[0]
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
  .art_profile img {
    width: 24px;
    height: 24px;
    overflow: hidden;
    border-radius: 50%;
    object-fit: cover;
  }
  .bot_art_images {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 5px;
  }
  .bot_art_images img {
    height: 187px;
    object-fit: cover;
  }
</style>
