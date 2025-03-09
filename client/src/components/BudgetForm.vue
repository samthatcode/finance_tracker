<template>
  <form class="p-4 border rounded bg-light" @submit.prevent="submitBudget">
    <div class="mb-3">
      <label for="budgetCategory" class="form-label">Category</label>
      <input
        type="text"
        id="budgetCategory"
        class="form-control"
        v-model="budget.category"
        placeholder="e.g., Groceries"
        required
      />
    </div>
    <div class="mb-3">
      <label for="budgetAmount" class="form-label">Budget Amount</label>
      <input
        type="number"
        id="budgetAmount"
        class="form-control"
        v-model="budget.amount"
        placeholder="0.00"
        step="0.01"
        required
      />
    </div>
    <div class="mb-3">
      <label for="month" class="form-label">Month</label>
      <input
        type="month"
        id="month"
        class="form-control"
        v-model="budget.month"
        required
      />
    </div>
    <button type="submit" class="btn btn-primary" :disabled="loading">
      <span
        v-if="loading"
        class="spinner-border spinner-border-sm me-2"
        role="status"
      ></span>
      {{ loading ? "Saving..." : "Set Budget" }}
    </button>
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
  </form>
</template>

<script>
import api from "../api";

export default {
  name: "BudgetForm",
  data() {
    return {
      budget: {
        category: "",
        amount: null,
        month: new Date().toISOString().slice(0, 7),
      },
      loading: false,
      error: null,
    };
  },
  methods: {
    async submitBudget() {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post("/create_budgets", this.budget, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.$emit("budget-added", response.data);
        this.resetForm();
      } catch (error) {
        this.error = error.response?.data?.message || "Failed to set budget";
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.budget = {
        category: "",
        amount: null,
        month: new Date().toISOString().slice(0, 7),
      };
    },
  },
};
</script>
