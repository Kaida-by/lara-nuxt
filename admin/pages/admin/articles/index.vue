<template>
  <div class="container">

    <div class="page">
      <div class="left_area">
        <div class="columns">
          <div class="category" v-for="category in categories">
            <div class="title">{{ category.title }}</div>
            <div class="count_article">{{ category.cat }}</div>
          </div>
        </div>
      </div>
      <div class="right_area">
        <div class="articles">
          <table>
            <tr>
              <th>Name</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
            <tr class="article" v-for="article in articles">
              <td><span>{{ article.title }}</span></td>
              <td><span>{{ article.entityStatus.code }}</span></td>
              <td class="actions">
                <div class="edit">
                  <nuxt-link :to="'/admin/articles/' + article.id">
                    edit
                  </nuxt-link>
                </div>
                <div class="delete">delete</div>
              </td>
            </tr>
          </table>
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
      categories: [],
      links: {}
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/admin/articles?page=' + this.page)
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
    },
    async fetchDataCategories() {
      await this.$axios.get('/admin/article-categories')
        .then((res) => {
          this.categories = res.data.categories
        })
        .catch(err => console.log(err))
    }
  },
  mounted () {
    this.fetchData()
    this.fetchDataCategories()
  }
}
</script>

<style scoped>
  .articles {
    margin-bottom: 20px;
  }
  .article h3, .article p {
    margin: 0;
  }
  .btns {
    display: flex;
  }
  .page {
    display: flex;
    width: 100%;
    height: 100vh;
  }
  .left_area {
    background-color: #a0aec0;
    width: 17%;
    height: 100%;
  }
  .right_area {
    background-color: #bbc2cb;
    width: 83%;
    height: 100%;
    position: relative;
  }
  .articles {
    width: 100%;
  }
  .btns {
    height: fit-content;
    justify-content: center;
  }
  table {
    width: 100%;
  }
  tr th, tr td {
    text-align: left;
  }
  table, th, td {
    border: 1px solid transparent;
    border-collapse: collapse;
  }
  table tr {
    background-color: #767676;
  }
  table tr:nth-child(2n) {
    background-color: #ededed;
  }
  table tr:first-child {
    background-color: #fff;
  }
  .actions {
    display: flex;
    justify-content: left;
  }
  .actions .edit {
    display: block;
    background-color: orange;
    padding: 10px;
    border-radius: 5px;
    margin-right: 5px;
  }
  .actions .edit a {
    text-decoration: unset;
    color: black;
  }
  .actions .delete {
    display: block;
    background-color: #d50505;
    padding: 10px;
    border-radius: 5px;
    margin-left: 5px;
  }
  .category {
    display: flex;
    justify-content: space-between;
  }
</style>
