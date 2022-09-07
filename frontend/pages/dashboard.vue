<template>
  <div class="container">
    <h2>Я люблю мою Наташку!!!</h2>
    <div class="image"><a href="https://ibb.co/bzHC4LJ"><img src="https://i.ibb.co/LnQcT65/12552.jpg" alt="12552" border="0"></a></div>
  </div>
</template>

<script>
export default {
  name: "dashboard",
  middleware: 'auth',
}
</script>

<style scoped>
  .image {
    width: 100%;
    height: 100vh;
    display: block;
  }
</style>
