<template>
  <div class="container">
    <organizations count="16" is_main=false></organizations>
  </div>
</template>

<script>
import Organizations from "~/components/organizations/Organizations";

export default {
  name: "index",
  components: {
    Organizations
  }
}
</script>

<style scoped>

</style>