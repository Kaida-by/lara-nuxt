<template>
  <div>
    <div class="articles">
      <div class="article" v-for="article in articles">
        <nuxt-link v-if="article.mainImageUrl" :to="'/articles/' + article.id">
          <div class="item">
            <div class="date">{{ article.created_at }}</div>
            <img class="item_img" :src="article.mainImageUrl" alt="">
            <h2>{{ article.title }}</h2>
          </div>
        </nuxt-link>
        <nuxt-link v-else :to="'/articles/' + article.id">
          <div class="item">
            <div class="date">{{ article.created_at }}</div>
            <img class="item_img" src="/public/assets/images/lg.jpg" alt="">
            <h2>{{ article.title }}</h2>
          </div>
        </nuxt-link>
      </div>
      <div v-if="is_main === 'true'" class="btns-main my-5">
        <div v-if="page > 1" class="prev_btn">
          <el-button @click="prevPage" round>prev</el-button>
        </div>
        <div v-else class="prev_btn">
          <el-button round>prev</el-button>
        </div>
        <div v-if="meta.next_page_url !== null" class="next_btn">
          <el-button @click="nextPage" round>next</el-button>
        </div>
        <div v-else class="next_btn">
          <el-button round>next</el-button>
        </div>
      </div>
    </div>
    <div v-if="is_main === 'false'" class="btns my-5">
      <div v-if="page > 1">
        <el-button @click="prevPage" round>prev</el-button>
      </div>
      <div v-else>
        <el-button round>prev</el-button>
      </div>
      <div v-if="meta.next_page_url !== null">
        <el-button @click="nextPage" round>next</el-button>
      </div>
      <div v-else>
        <el-button round>next</el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Articles",
  props: [
    'count',
    'is_main',
  ],
  data () {
    return {
      page: 1,
      articles: [],
      meta: {}
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/articles?page=' + this.page + '&count=' + this.count)
        .then((res) => {
          this.articles = res.data.data
          this.meta = res.data.meta
        })
        .catch(err => console.log(err))
    },
    nextPage() {
      this.page++;
      this.fetchData()
    },
    prevPage() {
      this.page--;
      this.fetchData()
    }
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>
.article {
  display: flex;
  z-index: 9;
}
.article a {
  width: 100%;
  display: block;
}
.article p {
  display: flex;
  align-items: center;
  padding-left: 20px;
}
.articles {
  margin-bottom: 20px;
  display: grid;
  grid-template-columns: repeat(4, 24%);
  gap: 20px;
  position: relative;
}
.article h2 {
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
.article h2, .article p {
  margin: 3px 0;
}
.btns {
  display: flex;
  gap: 4px;
  justify-content: center;
  height: fit-content;
}
.item_img {
  height: 300px;
  object-fit: cover;
}
.item {
  position: relative;
  height: 420px;
  background-color: white;
  overflow: hidden;
}
.articles .item img {
  margin-bottom: 15px;
  width: 100%;
}
.item h2,
.item p {
  padding: 0 15px;
}
.item h2 {
  font-size: 18px;
  font-weight: 600;
}
.item .date {
  position: absolute;
  background-color: white;
  opacity: 0.7;
  top: 7px;
  left: 7px;
  width: fit-content;
  border-radius: 15px;
  padding: 0 6px;
}
.btns-main {
  position: absolute;
  width: 100%;
  margin-top: 0;
  margin-bottom: 0;
  top: 50%;
  height: 40px;
  transform: translate(0, -50%)
}
.btns-main .prev_btn {
  left: -80px;
  position: absolute;
}
.btns-main .next_btn {
  right: -80px;
  position: absolute;
}
</style>
