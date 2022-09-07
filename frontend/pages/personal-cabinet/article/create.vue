<template>
  <div class="container">
    <p>Create</p>
    <form @submit.prevent="create">
      <label>Email: </label>
      <input v-model="form.title" type="text" name="name" :class="{ 'is-invalid': errors.title }" placeholder="title">
      <input v-model="form.description" type="text" name="description" :class="{ 'is-invalid': errors.description }" placeholder="description">
      <div class="invalid-feedback" v-if="errors.text">
        {{ errors.text[0] }}
      </div>
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
        author_id: this.$auth.user.id,
      },
      error: this.$route.query.error
    }
  },
  methods: {
    async create() {
      try {
        await this.$axios.post('/article/store', this.form);
      } catch(e) {
        return;
      }

      this.$router.push({name: 'index'});
    }
  }
}
</script>

<style scoped>

</style>
