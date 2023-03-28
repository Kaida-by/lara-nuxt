<template>
  <div class="container">
    <nuxt-link to="/articles">Articles</nuxt-link>
    <articles count="4" is_main=true></articles>

    <nuxt-link to="/posters">Posters</nuxt-link>
    <posters count="4" is_main=true></posters>
  </div>
</template>

<script>

import Articles from "~/components/articles/Articles";
import Posters from "~/components/posters/Posters";

export default {
  name: 'IndexPage',
  components: {
    Articles,
    Posters,
  }
}

</script>
