<template>
  <div class="center_area p-12 grid gap-4 grid-cols-5">
    <nuxt-link to="/admin/articles" class="entity">Articles</nuxt-link>
    <nuxt-link to="/admin/users" class="entity">Users</nuxt-link>
    <nuxt-link to="/admin/articles" class="entity">Announcements</nuxt-link>
    <nuxt-link to="/admin/cvs" class="entity">CVs</nuxt-link>
    <nuxt-link to="/admin/organizations" class="entity">Organizations</nuxt-link>
    <nuxt-link to="/admin/users" class="entity">Photo facts</nuxt-link>
    <nuxt-link to="/admin/articles" class="entity">Photo Galleries</nuxt-link>
    <nuxt-link to="/admin/posters" class="entity">Posters</nuxt-link>
    <nuxt-link to="/admin/vacancies" class="entity">Vacancies</nuxt-link>
  </div>
</template>

<script>
export default {
name: "CenterNav"
}
</script>

<style scoped>
  .entity {
    border: 1px solid white;
    color: white;
    @apply transition duration-300 p-6 rounded-md text-center hover:bg-slate-100 hover:text-inherit
  }
</style>
