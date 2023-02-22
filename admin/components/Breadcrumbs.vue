<template>
  <div>
    <a @click="hasHistory() ? $router.back() : $router.push('/')" href="#">
      Back
    </a>
  </div>
</template>

<script>
export default {
  methods: {
    hasHistory() {
      return this.$nuxt.context.from;
    }
  }
}
</script>

<style scoped>

</style>
