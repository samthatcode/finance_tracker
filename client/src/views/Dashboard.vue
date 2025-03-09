<template>
  <div class="container mt-5">
    <h2>Dashboard</h2>

    <!-- Loading State -->
    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else>
      <!-- Financial Summary -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Total Income</h5>
              <p class="card-text">${{ totalIncome.toFixed(2) }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Total Expenses</h5>
              <p class="card-text">${{ totalExpenses.toFixed(2) }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Balance</h5>
              <p class="card-text">
                ${{ (totalIncome - totalExpenses).toFixed(2) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Budget Form -->
      <h3>Set a Budget</h3>
      <BudgetForm @budget-added="fetchBudgets" />

      <!-- Budget List -->
      <h3 class="mt-4">Your Budgets</h3>
      <div v-if="budgets.length === 0" class="alert alert-info">
        No budgets set yet.
      </div>
      <div class="row" v-else>
        <div class="col-md-4" v-for="budget in budgets" :key="budget.id">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">{{ budget.category }}</h5>
              <p class="card-text">Budget: ${{ budget.amount }}</p>
              <p class="card-text">Month: {{ budget.month }}</p>
              <p class="card-text">
                Spent: ${{ getSpentForCategory(budget.category, budget.month) }}
              </p>
              <p
                :class="[
                  'card-text',
                  getRemaining(budget) < 0 ? 'text-danger' : 'text-success',
                ]"
              >
                Remaining: ${{ getRemaining(budget).toFixed(2) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Transaction Form -->
      <h3 class="mt-4">Add/Edit Transaction</h3>
      <TransactionForm
        :transaction-to-edit="transactionToEdit"
        @transaction-added="fetchTransactions"
        @transaction-updated="fetchTransactions"
        @cancel-edit="transactionToEdit = null"
      />

      <!-- Transactions and Chart -->
      <h3 class="mt-4">Your Transactions</h3>
      <div v-if="transactions.length === 0" class="alert alert-info">
        No transactions yet.
      </div>
      <TransactionList
        v-else
        :transactions="transactions"
        @transaction-deleted="fetchTransactions"
        @edit-transaction="startEditing"
      />
      <h3 class="mt-4">Spending by Category</h3>
      <div v-if="data.labels && data.labels.length" class="chart-container">
        <Bar :data="data" :options="options" />
      </div>
      <p v-else class="text-muted">No expense data to display yet.</p>
    </div>

    <!-- Error Display -->
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
  </div>
</template>

<script>
import TransactionForm from "../components/TransactionForm.vue";
import TransactionList from "../components/TransactionList.vue";
import BudgetForm from "../components/BudgetForm.vue";
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LinearScale,
  CategoryScale,
  BarElement,
} from "chart.js";
import api from "@/api";

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default {
  name: "DashboardView",
  components: {
    TransactionForm,
    TransactionList,
    BudgetForm,
    Bar,
  },
  data() {
    return {
      transactions: [],
      budgets: [],
      transactionToEdit: null,
      error: null,
      loading: false,
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
      data: {
        labels: [],
        datasets: [],
      },
    };
  },
  computed: {
    totalIncome() {
      return this.transactions
        .filter((t) => t.type === "income")
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);
    },
    totalExpenses() {
      return this.transactions
        .filter((t) => t.type === "expense")
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);
    },
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.loading = true;
      this.error = null;
      try {
        await Promise.all([this.fetchTransactions(), this.fetchBudgets()]);
        this.updateChartData();
      } catch (error) {
        this.error = "Failed to load data. Please try again.";
      } finally {
        this.loading = false;
      }
    },

    updateChartData() {
      const expenses = this.transactions.filter((t) => t.type === "expense");
      const categories = [...new Set(expenses.map((t) => t.category))];
      const amounts = categories.map((category) =>
        expenses
          .filter((t) => t.category === category)
          .reduce((sum, t) => sum + parseFloat(t.amount), 0)
      );
      this.chartData = {
        labels: categories,
        datasets: [
          {
            label: "Spending by Category",
            data: amounts,
            backgroundColor: [
              "#FF6384",
              "#36A2EB",
              "#FFCE56",
              "#4BC0C0",
              "#9966FF",
              "#FF9F40",
            ],
          },
        ],
      };
    },

    async fetchTransactions() {
      const response = await api.get("/get_user_transactions", {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      });
      this.transactions = response.data;
    },

    async fetchBudgets() {
      const response = await api.get("/get_user_budgets", {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      });
      this.budgets = response.data;
    },

    startEditing(transaction) {
      this.transactionToEdit = transaction;
    },

    getSpentForCategory(category, month) {
      return this.transactions
        .filter(
          (t) =>
            t.type === "expense" &&
            t.category === category &&
            t.date.startsWith(month)
        )
        .reduce((sum, t) => sum + parseFloat(t.amount), 0)
        .toFixed(2);
    },

    getRemaining(budget) {
      const spent = this.getSpentForCategory(budget.category, budget.month);
      return parseFloat(budget.amount) - parseFloat(spent);
    },
  },
};
</script>

<style scoped>
.chart-container {
  position: relative;
  height: 400px;
  width: 100%;
  max-width: 600px;
  margin: 0 auto;
}
.card {
  margin-bottom: 1rem;
}
@media (max-width: 768px) {
  h3 {
    font-size: 1.25rem;
  }
  .card-title {
    font-size: 1rem;
  }
}
</style>
