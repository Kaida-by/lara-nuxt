<template>
  <div class="container">
    <posters count="16" is_main=false></posters>
  </div>
</template>

<script>
import Posters from "~/components/posters/Posters";
export default {
  name: "index",
  components: {Posters}
}
</script>

<style scoped>

</style>
