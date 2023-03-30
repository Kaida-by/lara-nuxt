<template>
  <div class="container">
    <template v-if="$auth.loggedIn">
      <div>
        <div>
          {{ $auth.user.name }}
        </div>
        <div>
          <div>Article:</div>
          <div class="create-article">
            <nuxt-link to="/personal-cabinet/article/create">Create</nuxt-link>
            <nuxt-link to="/personal-cabinet/article/">Show all</nuxt-link>
          </div>

          <div>Posters:</div>
          <div class="create-poster">
            <nuxt-link to="/personal-cabinet/poster/create">Create</nuxt-link>
            <nuxt-link to="/personal-cabinet/poster/">Show all</nuxt-link>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
name: "index"
}
</script>

<style scoped>

</style>
