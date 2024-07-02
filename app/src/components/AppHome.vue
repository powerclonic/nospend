<template>
  <v-dialog :model-value="loading" persistent>
    <v-card color="accent">
      <template #title>Buscando seus dados</template>
      <template #append>
        <v-progress-circular color="primary" indeterminate />
      </template>
    </v-card>
  </v-dialog>
  <v-container class="d-flex flex-column ga-2" v-if="data">
    <article>
      <p class="text-subtitle-1 text-secondary">Bom dia,</p>
      <p class="text-h4 text-light font-weight-bold">{{ data.name }}!</p>
    </article>
    <v-container
      class="bg-accent rounded-lg pa-2 d-flex flex-column flex-wrap ga-2"
    >
      <div class="d-flex justify-space-between align-center">
        <p class="text-h6">Despesas de hoje</p>
        <v-icon icon="mdi-clock-outline" color="primary" />
      </div>
      <expense-card
        v-for="(value, index) in data.today_expenses"
        :key="index"
        :expense="value"
      />
      <v-card
        v-if="data.today_expenses.length <= 0"
        title="Tudo tranquilo"
        subtitle="Nenhuma despesa para o dia de hoje"
        color="background"
      >
        <template #append>
          <v-icon icon="mdi-check" color="primary" />
        </template>
      </v-card>
    </v-container>
    <card-button
      title="Todas as despesas"
      icon="mdi-chevron-right"
      @click="$router.push('/expenses')"
    />
    <card-button title="Nova despesa" icon="mdi-plus">
      <new-expense />
    </card-button>
    <v-container
      class="bg-accent rounded-lg pa-2 d-flex flex-column flex-wrap ga-2"
    >
      <div class="d-flex justify-space-between align-center">
        <p class="text-h6">Resumo do mês</p>
        <v-icon icon="mdi-chart-donut" color="primary" />
      </div>
      <expense-stat
        title="Nº de despesas"
        :value="data.month_statistics.expenses_quantity"
        icon="mdi-numeric"
      />
      <expense-stat
        title="Total em despesas"
        :value="data.month_statistics.expenses_total_value"
        icon="mdi-wallet"
        monetary
      />
      <expense-stat
        title="Total já pago"
        :value="data.month_statistics.expenses_total_paid"
        icon="mdi-currency-usd"
        monetary
      />
      <expense-stat
        title="Nº de despesas"
        :value="data.month_statistics.expenses_total_unpaid"
        icon="mdi-currency-usd-off"
        monetary
      />
      <expense-stat
        title="Despesas não recorrentes"
        :value="data.month_statistics.expenses_total_not_recurrent"
        icon="mdi-sync-alert"
        monetary
      />
    </v-container>
  </v-container>
</template>

<script setup lang="ts">
import user from "@/services/api/user";
import type { Dashboard } from "@/types";

const loading = ref(false);
const data: Ref<Dashboard | null> = ref(null);

const getData = async () => {
  try {
    loading.value = true;
    data.value = (await user.dashboard()).data.data;
  } catch (error) {
    console.error("Erro ao buscar dados", error);
  } finally {
    loading.value = false;
  }
};

// const data: Dashboard = {
//   name: "Matheus",
//   today_expenses: [
//     {
//       id: 1,
//       value: 100,
//       name: "Fatura",
//       due_date: "2024-06-12",
//       status: "PAID",
//       created_at: "2024-06-10",
//       recurrent: true,
//       auto_pay: false,
//       payment_source: "Sicredi",
//       category: "Faturas",
//     },
//     {
//       id: 1,
//       value: 100,
//       name: "Fatur2a",
//       due_date: "2024-06-12",
//       status: "NOT_PAID",
//       created_at: "2024-06-10",
//       recurrent: true,
//       auto_pay: true,
//     },
//     {
//       id: 1,
//       value: 100,
//       name: "Fat3ura",
//       due_date: "2024-06-12",
//       status: "NOT_PAID",
//       created_at: "2024-06-10",
//       recurrent: true,
//       auto_pay: true,
//     },
//   ],
//   month_statistics: {
//     expenses_quantity: 10,
//     expenses_total_paid: 2500,
//     expenses_total_unpaid: 3500,
//     expenses_total_value: 6000,
//     expenses_total_not_recurrent: 756.5,
//   },
// };

getData();
</script>
