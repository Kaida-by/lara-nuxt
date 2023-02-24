<template>
  <div class="w-full">
    <breadcrumbs></breadcrumbs>
    <div class="page">

      <div class="left_area">
        <div class="columns_categories">
          <div class="category" v-for="category in categories">
            <div class="title">{{ category.title }}</div>
            <div class="count_article">{{ category.cat }}</div>
          </div>
        </div>
      </div>

      <div class="right_area">
        <el-table
          stripe
          :data="articles.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
          style="width: 100%">
          <el-table-column label="Title" prop="title"></el-table-column>
          <el-table-column label="User email" prop="user.email"></el-table-column>
<!--          <el-table-column label="Description" prop="description"></el-table-column>-->
          Search
          <el-table-column
            align="right">
            <template slot="header" slot-scope="scope">
              <el-input
                v-model="searchInput"
                @input="searching" autocomplete="off"
                size="mini"
                placeholder="Type something..."/>
            </template>
            <template slot-scope="scope">
              <nuxt-link :to="'/admin/articles/' + articles[scope.$index].id">
                <el-button
                  size="mini"
                  type="warning">edit</el-button>
              </nuxt-link>
              <el-button
                size="mini"
                type="danger" @click="deleteArticle(articles[scope.$index].id)">Delete</el-button>
            </template>
          </el-table-column>
        </el-table>
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

    </div>
  </div>
</template>

<script>
import axios from "axios";
import Breadcrumbs from "~/components/Breadcrumbs";

export default {
  name: "index",
  components: {
    Breadcrumbs
  },
  data () {
    return {
      page: 1,
      articles: [],
      categories: [],
      links: {},
      search: '',
      searchInput: '',
    }
  },
  methods: {
    async searching() {
      if (this.searchInput.length > 2) {
        await this.$axios.get('/admin/article/search', {
          params: {
            query: this.searchInput
          }
        })
          .then(response => {
            this.articles = response.data.data;
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        await this.$axios.get('/admin/articles?page=' + this.page)
          .then((res) => {
            this.articles = res.data.data
            this.links = res.data.links
          })
          .catch(err => console.log(err))
      }
    },
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
    },
    deleteArticle(id) {
      if (confirm("Do you really want to delete this Article?")) {
        try {
          this.$axios.delete('/admin/article/delete/' + id).then(response => {
            this.fetchData();
          });
        } catch(e) {
          return;
        }
      }
    }
  },
  mounted () {
    this.fetchData()
    this.fetchDataCategories()
  }
}
</script>

<style scoped>
  .category {
    display: flex;
    padding: 8px 12px;
    justify-content: space-between;
    cursor: pointer;
  }
  .category:hover {
    @apply bg-slate-400
  }
  .category .title {
    font-size: 14px;
    line-height: 23px;
    color: #222222;
  }
  .count_article {
    color: #222222;
    font-size: 12px;
  }
  .btns {
    display: flex;
    gap: 4px;
    justify-content: center;
  }
  .page {
    display: flex;
    width: 100%;
    border-bottom: 1px solid #FAFAFA;
    background-color: #EBEEF5;
  }
  .left_area {
    width: 12%;
    height: 100%;
  }
  .right_area {
    width: 88%;
    height: 100%;
    position: relative;
    border-left: 1px solid #FAFAFA;
  }
  .btns {
    height: fit-content;
    justify-content: center;
  }
  /*.el-table ::v-deep .el-table__row {*/
  /*  @apply bg-gray-300;*/
  /*}*/
</style>
