<template>
  <div class="container">
    <h2>Welcome dashboard</h2>
  </div>
</template>

<script>
export default {
  name: "dashboard",
  middleware: 'auth',
}
</script>
