<template>
  <div class="container">
    <div class="vacancy">
      <form @submit.prevent="update">

        <label>Title: </label>
        <input v-model="form.title" type="text" name="title" :class="{ 'is-invalid': errors.text }" placeholder="title">
        <div class="invalid-feedback" v-if="errors.title">{{ errors.title[0] }}</div>

        <label>Description: </label>
        <input v-model="form.description" type="textarea" name="description" :class="{ 'is-invalid': errors.text }" placeholder="description">

        <label>Phone: </label>
        <input type="tel" v-mask="'+375 (##) ### ## ##'" v-model="form.phone.number">
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
  data() {
    return {
      form: {
        title: '',
        description: '',
        phone: '',
      },
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/vacancy/edit/' + this.$route.params.id)
        .then((res) => {
          const vacancy = res.data
          for (let key in this.form) {
            this.form[key] = vacancy[key]
          }
        })
        .catch(err => console.log(err))
    },
    async update() {
      try {
        await this.$axios.post('/vacancy/' + this.$route.params.id, this.form, {})
      } catch(e) {
        return;
      }
    },
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>

</style>
