<template>
  <div class="mx-auto align-middle flex w-100">
    <center-nav></center-nav>
  </div>
</template>

<script>

import CenterNav from "../../layouts/partials/admin/CenterNav";

export default {
  name: "admin",
  middleware: 'auth',
  components: {
    CenterNav
  }
}
</script>

<style scoped>
  .container {
    display: flex;
    width: 100%;
  }
  .center_area {
    background-color: #a0aec0;
    width: 100%;
  }
</style>
