<template>
  <div class="container">
    <div class="article">
      <form @submit.prevent="update">
        <label>Title: </label>
        <input
          v-model="article.title"
          type="text"
          name="title"
          :class="{ 'is-invalid': errors.text }"
          placeholder="title"
        >
        <div class="invalid-feedback" v-if="errors.text">
          {{ errors.text[0] }}
        </div>
        <label>Description: </label>
        <input
          v-model="article.description"
          type="text"
          name="description"
          placeholder="description"
        >
        <input type="submit" value="Update">
      </form>
    </div>
    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  name: "_id",
  data() {
    return {
      article: [],
      form: {
        title: '',
        description: '',
      },
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/article/edit/' + this.$route.params.id)
        .then((res) => {
          this.article = res.data.data[0]
        })
        .catch(err => console.log(err))
    },
    async update() {
      try {
        await this.$axios.patch('/article/' + this.$route.params.id, this.article);
      } catch(e) {
        return;
      }

      // this.$router.push({name: 'admin'});
    }
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>

</style>
