<template>
  <div class="container mt-5">
    <h2>Register</h2>
    <form @submit.prevent="register">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          id="name"
          class="form-control"
          v-model="form.name"
          placeholder="Your Name"
          required
        />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          class="form-control"
          v-model="form.email"
          placeholder="Email"
          required
        />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          class="form-control"
          v-model="form.password"
          placeholder="Password (min 8 characters)"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary" :disabled="loading">
        <span
          v-if="loading"
          class="spinner-border spinner-border-sm me-2"
          role="status"
        ></span>
        {{ loading ? "Registering..." : "Register" }}
      </button>
    </form>
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
    <p class="mt-3">
      Already have an account?
      <router-link to="/login" class="text-primary">Login here</router-link>
    </p>
  </div>
</template>

<script>
import api from "../api";

export default {
  name: "RegisterView",
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
      },
      loading: false,
      error: null,
    };
  },
  methods: {
    async register() {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post("/register_user", this.form);
        localStorage.setItem("token", response.data.token);
        this.$router.push("/dashboard");
      } catch (error) {
        this.error = error.response?.data?.message || "Registration failed";
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
