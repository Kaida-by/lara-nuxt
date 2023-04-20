<template>
  <div class="w-full">
    <breadcrumbs></breadcrumbs>
    <div class="page">

      <div class="left_area">
        <div class="columns_categories">
          <div class="category" @click.prevent="filterByCategory(0)">
            <div class="title">Все</div>
            <div class="count_vacancy">{{ countVacancies }}</div>
          </div>
        </div>
      </div>

      <div class="right_area">
        <el-table
          stripe
          :data="vacancies.filter(
              data => !search ||
              data.name.toLowerCase().includes(search.toLowerCase())
          )"
          empty-text="No data"
          style="width: 100%">
          <el-table-column label="Id" prop="id"></el-table-column>
          <el-table-column sortable label="Title" prop="title"></el-table-column>
          <el-table-column sortable label="Status" prop="status.code"></el-table-column>
          <el-table-column sortable label="Created At" prop="created_at"></el-table-column>
          <el-table-column sortable label="Updated At" prop="updated_at"></el-table-column>
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
              <nuxt-link :to="'/admin/vacancies/' + vacancies[scope.$index].id">
                <el-button
                  size="mini"
                  type="warning">edit</el-button>
              </nuxt-link>
              <el-button
                size="mini"
                type="danger" @click="deleteVacancy(vacancies[scope.$index].id)">Delete</el-button>
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
          <div v-if="meta.next_page_url !== null">
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
      vacancies: [],
      categories: [],
      countVacancies: 0,
      meta: {},
      search: '',
      searchInput: '',
      categoryId: 0,
    }
  },
  methods: {
    async searching() {
      if (this.searchInput.length > 2) {
        await this.$axios.get('/admin/vacancy/search', {
          params: {
            query: this.searchInput
          }
        })
          .then(response => {
            this.vacancies = response.data;
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        await this.$axios.get('/admin/vacancies?page=' + this.page)
          .then((res) => {
            this.vacancies = res.data.data
            this.meta = res.data.meta
          })
          .catch(err => console.log(err))
      }
    },
    async fetchData() {
      await this.$axios.get(
          '/admin/vacancies?page=' + this.page +
          '&categoryId=' + this.categoryId
      )
        .then((res) => {
          this.vacancies = res.data.data
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
    },
    async fetchDataCategories() {
      await this.$axios.get('/admin/vacancy-categories?categoryId=' + 2)
        .then((res) => {
          this.categories = res.data.categories
        })
        .catch(err => console.log(err))
    },
    deleteVacancy(id) {
      if (confirm("Do you really want to delete this Vacancy?")) {
        try {
          this.$axios.delete('/admin/vacancy/delete/' + id).then(response => {
            this.fetchData();
          });
        } catch(e) {
          return;
        }
      }
    },
    async countAllVacancies() {
      await this.$axios.get('/admin/count-vacancies/')
          .then((res) => {
            this.countVacancies = res.data
          })
          .catch(err => console.log(err))
    },
    filterByCategory(id) {
      this.categoryId = id;
      this.fetchData()
    },
  },
  mounted () {
    this.fetchData()
    this.fetchDataCategories()
    this.countAllVacancies()
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
  .count_vacancy {
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
</style>
