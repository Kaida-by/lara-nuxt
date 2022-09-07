<template>
  <div class="container">
    <template v-if="$auth.loggedIn">
      <div>
        <div>
          {{ $auth.user.name }}
        </div>
        <div>
          <div class="create-article">
            <nuxt-link to="personal-cabinet/article/create">Create</nuxt-link>
            <nuxt-link to="personal-cabinet/article/">My Articles</nuxt-link>
          </div>
          <div class="show-articles">

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
