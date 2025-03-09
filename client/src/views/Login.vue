<template>
  <div class="container mt-5">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="mb-3">
        <input
          type="email"
          class="form-control"
          v-model="email"
          placeholder="Email"
          required
        />
      </div>
      <div class="mb-3">
        <input
          type="password"
          class="form-control"
          v-model="password"
          placeholder="Password"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary" :disabled="loading">
        <span
          v-if="loading"
          class="spinner-border spinner-border-sm me-2"
          role="status"
        ></span>
        {{ loading ? "Logging in..." : "Login" }}
      </button>
    </form>
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
    <p class="mt-3">
      Don't have an account?
      <router-link to="/register" class="text-primary"
        >Register here</router-link
      >
    </p>
  </div>
</template>

<script>
// import axios from "axios";
import api from "../api";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: null,
      loading: false,
    };
  },
  methods: {
    async login() {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post("/login_user", {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem("token", response.data.token);
        this.$router.push("/dashboard");
      } catch (error) {
        this.error = error.response?.data?.message || "Login failed";
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
