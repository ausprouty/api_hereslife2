<template>
  <NavBar />
  <router-view></router-view> <!-- This renders the component matched by the route -->
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';

  // You can leave this empty if no script logic is needed in the root component.
</script>

<style scoped>
#app{
  background-color: aqua;
}
  /* Add any global styles here, or leave this empty */
</style>
