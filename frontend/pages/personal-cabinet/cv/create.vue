<template>
  <div class="container">
    <p>Create</p>
    <form @submit.prevent="create">

      <label>Name: </label>
      <input v-model="form.title" type="text" name="name" :class="{ 'is-invalid': errors.title }" placeholder="title">
      <div class="invalid-feedback" v-if="errors.title">
        {{ errors.title[0] }}
      </div>

      <label>Description: </label>
      <input v-model="form.description" type="textarea" name="description" :class="{ 'is-invalid': errors.text }" placeholder="description">
      <div class="invalid-feedback" v-if="errors.description">
        {{ errors.description[0] }}
      </div>

      <label>Phone: </label>
      <input type="tel" v-mask="'+375 (##) ### ## ##'" v-model="form.phone.number">

      <input type="submit" value="Create">
    </form>

    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  name: "create",
  middleware: 'auth',
  data() {
    return {
      form: {
        title: '',
        description: '',
        phone: {
          number: ''
        },
        author_id: this.$auth.user.id,
      },
      error: this.$route.query.error,
      cte_id: '',
    }
  },
  methods: {
    async create() {
      try {
        await this.$axios.post('/cv/' + this.cte_id, this.form, {})
      } catch (e) {
        console.log(this.errors)
      }
    },
    async createTemporaryCv() {
      await this.$axios.post('/cv-cte', {}, {})
        .then(result => {
          this.cte_id = result.data.data
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
  mounted() {
    this.createTemporaryCv()
  }
}
</script>

<style scoped>

</style>
