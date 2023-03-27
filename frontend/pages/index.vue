<template>
  <div class="container">
    <nuxt-link to="/articles">Articles</nuxt-link>
    <articles count="4" is_main=true></articles>
  </div>
</template>

<script>

import Articles from "~/components/articles/Articles";

export default {
  name: 'IndexPage',
  components: {
    Articles,
  }
}

</script>
