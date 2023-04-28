<template>
  <div class="container">
    <nuxt-link to="/articles">Articles</nuxt-link>
    <articles count="4" is_main=true></articles>

    <nuxt-link to="/posters">Posters</nuxt-link>
    <posters count="4" is_main=true></posters>

    <nuxt-link to="/cvs">CVs</nuxt-link>
    <cvs count="4" is_main=true></cvs>

    <nuxt-link to="/vacancies">Vacancies</nuxt-link>
    <vacancies count="4" is_main=true></vacancies>

    <nuxt-link to="/vacancies">Organizations</nuxt-link>
    <organizations count="4" is_main=true></organizations>
  </div>
</template>

<script>

import Articles from "~/components/articles/Articles";
import Posters from "~/components/posters/Posters";
import Cvs from "~/components/cvs/Cvs";
import Vacancies from "~/components/vacancies/Vacancies";
import Organizations from "~/components/organizations/Organizations";

export default {
  name: 'IndexPage',
  components: {
    Articles,
    Posters,
    Cvs,
    Vacancies,
    Organizations,
  }
}

</script>
