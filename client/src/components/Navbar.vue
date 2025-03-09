<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">Finance Tracker</router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item" v-if="!isLoggedIn">
            <router-link class="nav-link" to="/login">Login</router-link>
          </li>
          <li class="nav-item" v-if="!isLoggedIn">
            <router-link class="nav-link" to="/register">Register</router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn">
            <router-link class="nav-link" to="/dashboard"
              >Dashboard</router-link
            >
          </li>
          <li class="nav-item" v-if="isLoggedIn">
            <button
              class="btn btn-outline-danger"
              :class="{ 'w-100': !isLargeScreen }"
              @click="logout"
              :disabled="loggingOut"
            >
              <span
                v-if="loggingOut"
                class="spinner-border spinner-border-sm me-1"
                role="status"
                aria-hidden="true"
              ></span>
              {{ loggingOut ? "Logging Out..." : "Logout" }}
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import api from "../api";

export default {
  name: "Navbar",
  data() {
    return {
      loggingOut: false,
      isLargeScreen: window.innerWidth > 991,
    };
  },
  computed: {
    isLoggedIn() {
      return !!localStorage.getItem("token");
    },
  },
  methods: {
    async logout() {
      this.loggingOut = true;
      try {
        await api.post(
          "/logout_user",
          {},
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        localStorage.removeItem("token");
        this.$router.push("/login");
      } catch (error) {
        console.error("Logout failed:", error);
        alert("Failed to log out. Please try again.");
      } finally {
        this.loggingOut = false;
      }
    },
    handleResize() {
      this.isLargeScreen = window.innerWidth > 991;
    },
  },
  mounted() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.handleResize);
  },
};
</script>

<style scoped>
.navbar-nav .nav-link {
  padding: 0.5rem 1rem;
}
.btn-outline-danger {
  margin-left: 1rem; /* Space from links on desktop */
}
@media (max-width: 991px) {
  .navbar-nav {
    padding: 0.5rem 0;
  }
  .nav-item {
    margin-bottom: 0.5rem;
  }
  .btn-outline-danger {
    margin-left: 0;
    padding: 0.5rem 1rem; /* Larger touch target */
  }
}
</style>
