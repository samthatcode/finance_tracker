<template>
  <main id="app" class="d-flex flex-column min-vh-100">
    <Navbar />
    <RouterView class="flex-grow-1" />
    <Footer />
  </main>
</template>

<script>
import Footer from "./components/Footer.vue";
import Navbar from "./components/Navbar.vue";
export default {
  components: {
    Navbar,
    Footer,
  },
};
</script>

<style scoped></style>
