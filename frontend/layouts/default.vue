<template>
  <div>
    <top-nav></top-nav>
      <Nuxt />
  </div>
</template>

<script>

import TopNav from "~/layouts/partials/TopNav";

export default {
  components: {
    TopNav
  }
}

</script>
