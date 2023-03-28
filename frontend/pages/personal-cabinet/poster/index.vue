<template>
  <div class="container">
    <div class="posters">
      <div class="poster" v-for="poster in posters">
        <h2>{{ poster.title }}</h2>
        <!--        <p>{{ poster.description }}</p>-->
        <nuxt-link :to="'/personal-cabinet/poster/' + poster.id">
          edit
        </nuxt-link>
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
      posters: [],
      links: {}
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/my-posters?page=' + this.page)
        .then((res) => {
          this.posters = res.data.data
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

</style>
