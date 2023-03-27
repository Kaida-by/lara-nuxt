<template>
  <div class="container">
    <articles count="16" is_main=false></articles>
  </div>
</template>

<script>
import Articles from "~/components/articles/Articles";

export default {
  name: "index",
  components: {
    Articles
  },
}
</script>

<style scoped>

</style>
