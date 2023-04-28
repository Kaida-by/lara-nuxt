<template>
  <div>
    <div class="organizations">
      <div class="organization" v-for="organization in organizations">
        <nuxt-link v-if=" organization.title" :to="'/organizations/' + organization.id">
          <div>{{ organization.title}}</div>
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
  name: "Organizations",
  props: [
    'count',
    'is_main',
  ],
  data () {
    return {
      page: 1,
      organizations: [],
      meta: {}
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/organizations?page=' + this.page + '&count=' + this.count)
          .then((res) => {
            this.organizations = res.data.data
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

</style>