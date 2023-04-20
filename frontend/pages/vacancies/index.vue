<template>
  <div class="container">
    <vacancies count="16" is_main=false></vacancies>
  </div>
</template>

<script>
import Vacancies from "~/components/vacancies/Vacancies";

export default {
  name: "index",
  components: {
    Vacancies
  }
}
</script>

<style scoped>

</style>