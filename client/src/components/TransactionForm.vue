<template>
  <form class="p-4 border rounded bg-light" @submit.prevent="submitTransaction">
    <div class="mb-3">
      <label for="type" class="form-label">Type</label>
      <select id="type" class="form-select" v-model="transaction.type" required>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input
        type="text"
        id="category"
        class="form-control"
        v-model="transaction.category"
        placeholder="e.g., Salary, Groceries"
        required
      />
    </div>
    <div class="mb-3">
      <label for="amount" class="form-label">Amount</label>
      <input
        type="number"
        id="amount"
        class="form-control"
        v-model="transaction.amount"
        placeholder="0.00"
        step="0.01"
        required
      />
    </div>
    <div class="mb-3">
      <label for="date" class="form-label">Date</label>
      <input
        type="date"
        id="date"
        class="form-control"
        v-model="transaction.date"
        required
      />
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea
        id="description"
        class="form-control"
        v-model="transaction.description"
        placeholder="Optional notes"
      ></textarea>
    </div>
    <button type="submit" class="btn btn-primary" :disabled="loading">
      <span
        v-if="loading"
        class="spinner-border spinner-border-sm me-2"
        role="status"
      ></span>
      {{
        loading
          ? "Saving..."
          : editMode
          ? "Update Transaction"
          : "Add Transaction"
      }}
    </button>
    <button
      v-if="editMode"
      type="button"
      class="btn btn-secondary ms-2"
      @click="cancelEdit"
    >
      Cancel
    </button>
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
  </form>
</template>

<script>
import api from "../api";

export default {
  name: "TransactionForm",
  props: {
    transactionToEdit: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      transaction: {
        type: "income",
        category: "",
        amount: null,
        description: "",
        date: new Date().toISOString().split("T")[0],
      },
      loading: false,
      error: null,
      editMode: false,
    };
  },
  watch: {
    transactionToEdit(newVal) {
      if (newVal) {
        this.transaction = { ...newVal };
        this.editMode = true;
      } else {
        this.resetForm();
        this.editMode = false;
      }
    },
  },
  methods: {
    async submitTransaction() {
      this.loading = true;
      this.error = null;
      try {
        const url = this.editMode
          ? `/update_transactions/${this.transaction.id}`
          : "/create_transactions";
        const method = this.editMode ? "put" : "post";
        const response = await api[method](url, this.transaction, {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });
        this.$emit(
          this.editMode ? "transaction-updated" : "transaction-added",
          response.data
        );
        this.resetForm();
        this.editMode = false;
      } catch (error) {
        this.error =
          error.response?.data?.message || "Failed to save transaction";
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.transaction = {
        type: "income",
        category: "",
        amount: null,
        description: "",
        date: new Date().toISOString().split("T")[0],
      };
    },
    cancelEdit() {
      this.resetForm();
      this.editMode = false;
      this.$emit("cancel-edit");
    },
  },
};
</script>
