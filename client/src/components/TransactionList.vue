<template>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Type</th>
          <th>Category</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions" :key="transaction.id">
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.category }}</td>
          <td>${{ transaction.amount }}</td>
          <td>{{ transaction.date }}</td>
          <td>{{ transaction.description || "-" }}</td>
          <td>
            <button
              class="btn btn-sm btn-warning me-2"
              @click="editTransaction(transaction)"
            >
              Edit
            </button>
            <button
              class="btn btn-sm btn-danger"
              @click="deleteTransaction(transaction.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <p v-if="error" class="text-danger">{{ error }}</p>
  </div>
</template>

<script>
import api from "@/api";

export default {
  name: "TransactionList",
  props: {
    transactions: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      error: null,
    };
  },
  methods: {
    async deleteTransaction(id) {
      if (confirm("Are you sure you want to delete this transaction?")) {
        try {
          await api.delete(`/delete_transactions/${id}`, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });
          this.$emit("transaction-deleted", id);
        } catch (error) {
          this.error =
            error.response?.data?.message || "Failed to delete transaction";
        }
      }
    },
    editTransaction(transaction) {
      this.$emit("edit-transaction", transaction);
    },
  },
};
</script>
<style scoped>
.table-responsive {
  overflow-x: auto;
}
@media (max-width: 576px) {
  .btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }
}
</style>
