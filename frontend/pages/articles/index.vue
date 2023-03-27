<template>
  <div class="container">
    <div class="articles">
      <div class="article" v-for="article in articles">
        <nuxt-link v-if="article.mainImageUrl" :to="'/articles/' + article.id">
          <div class="item">
            <div class="date">{{ article.created_at }}</div>
            <img class="item_img" :src="article.mainImageUrl" alt="">
            <h2 v-if="article.title.length > 30">{{ article.title.substr(0, 33) }} ...</h2>
            <h2 v-else>{{ article.title }}</h2>
          </div>
        </nuxt-link>
        <nuxt-link v-else :to="'/articles/' + article.id">
          <div class="item">
            <div class="date">{{ article.created_at }}</div>
            <img class="item_img" src="/public/assets/images/lg.jpg" alt="">
            <h2 v-if="article.title.length > 30">{{ article.title.substr(0, 33) }} ...</h2>
            <h2 v-else>{{ article.title }}</h2>
          </div>
        </nuxt-link>
      </div>
    </div>
    <div class="btns my-5">
      <div v-if="page > 1">
        <el-button @click="prevPage" round>prev</el-button>
      </div>
      <div v-else>
        <el-button round>prev</el-button>
      </div>
      <div v-if="links.next !== null">
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
  name: "index",
  data () {
    return {
      page: 1,
      articles: [],
      links: {}
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/articles?page=' + this.page)
        .then((res) => {
          this.articles = res.data.data
          this.links = res.data.links
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
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
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
</style>
