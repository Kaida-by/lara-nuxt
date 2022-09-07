<template>
  <div class="container">
    <div class="articles">
      <div class="article" v-for="article in articles">
        <h2>{{ article.title }}</h2>
        <p>{{ article.description }}</p>
      </div>
    </div>
    <div class="btns">
      <div v-if="page > 1">
        <button @click="prevPage">PREV</button>
      </div>
      <div v-else>
        <button>PREV</button>
      </div>
      <div v-if="links.next !== null">
        <button @click="nextPage">NEXT</button>
      </div>
      <div v-else>
        <button>NEXT</button>
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
  .article p {
    display: flex;
    align-items: center;
    padding-left: 20px;
  }
  .articles {
    margin-bottom: 20px;
  }
  .article h2, .article p {
    margin: 3px 0;
  }
  .btns {
    display: flex;
  }
</style>
