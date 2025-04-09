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
      <p class="text-subtitle-1 text-secondary">{{ greeting }},</p>
      <p class="text-h4 text-light font-weight-bold">{{ data.name }}!</p>
    </article>
    <v-card color="accent" rounded="lg">
      <template #title> Despesas de hoje </template>
      <template #subtitle> Mostrando as primeiras 3 despesas </template>
      <template #append>
        <v-icon icon="mdi-clock-outline" color="primary" />
      </template>
      <template #text>
        <div class="d-flex flex-column ga-2">
          <expense-card v-for="(value, index) in data.today_expenses" :key="index" :expense="value" />
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
        </div>
      </template>
    </v-card>
    <card-button title="Todas as despesas" icon="mdi-chevron-right" @click="$router.push('/expenses')" />
    <card-button title="Nova despesa" icon="mdi-plus">
      <new-expense @updated="() => getData()" />
    </card-button>
    <v-card color="accent" rounded="lg">
      <template #title> Resumo do mês </template>
      <template #subtitle> Estatísticas gerais do mês atual</template>
      <template #append>
        <v-icon icon="mdi-clock-outline" color="primary" />
      </template>
      <template #text>
        <div class="d-flex flex-column ga-2">
          <expense-stat title="Nº de despesas" :value="data.month_statistics.expenses_quantity" icon="mdi-numeric" />
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
            title="Total não pago"
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
        </div>
      </template>
    </v-card>
    <v-card color="accent" rounded="lg">
      <template #title> Gráficos </template>
      <template #subtitle> Visualize seus gastos de forma gráfica </template>
      <template #append>
        <v-icon icon="mdi-chart-pie" color="primary" />
      </template>
      <template #text>
        <div class="d-flex flex-column ga-2">
          <doughnut-chart
            :chartData="recurrencyChartData"
            title="Recorrência"
            subtitle="Valor total de despesas"
            icon="mdi-repeat"
          />
          <doughnut-chart
            title="Categorias"
            subtitle="Valor total por categoria"
            :chart-data="categoryChartData"
            icon="mdi-tag"
          />
          <doughnut-chart
            title="Forma"
            subtitle="Valor total por forma de pagamento"
            :chart-data="paymentMethodChartData"
            icon="mdi-cash"
          />
          <doughnut-chart
            title="Fonte"
            subtitle="Valor total por fonte de pagamento"
            :chart-data="paymnentSourceChartData"
            icon="mdi-bank"
          />
        </div>
      </template>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import user from "@/services/api/user";

import type { Dashboard } from "@/types";
import type { ChartData } from "chart.js";

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

getData();

const greeting = computed(() => {
  const now = new Date();

  if (now.getHours() < 12) {
    return "Bom dia";
  }

  if (now.getHours() < 18) {
    return "Boa tarde";
  }

  return "Boa noite";
});


const recurrencyChartData: ComputedRef<ChartData<"doughnut", number[], unknown>> = computed(() => {
  return {
    labels: [
      'Recorrente',
      'Não Recorrente',
    ],
    datasets: [{
      data: [
        data.value?.month_statistics.expenses_total_recurrent || 0,
        data.value?.month_statistics.expenses_total_not_recurrent || 0,
      ],
      backgroundColor: [
        '#849c02',
        '#2D6E7E'
      ],
      borderColor: '#153B44'
    }],
  }
});


const calculateBackgroundColor = (items: { total_value: number }[]) => {
  const maxValue = Math.max(...items.map((item) => item.total_value));
  return items.map((item) => {
    const percentage = item.total_value / maxValue;
    const baseColor = [198, 222, 65]; // RGB for #C6DE41
    const darkenedColor = baseColor.map((c) => Math.floor(c * percentage)); // Darken based on percentage
    return `rgb(${darkenedColor[0]}, ${darkenedColor[1]}, ${darkenedColor[2]})`;
  });
};

const categoryChartData: ComputedRef<ChartData<"doughnut", number[], unknown>> = computed(() => {
  return {
    labels: data.value?.expenses_by_category.map((v) => v.category),
    datasets: [{
      data: data.value ? data.value.expenses_by_category.map((v) => v.total_value) : [],
      backgroundColor: data.value ? calculateBackgroundColor(data.value.expenses_by_category) : [],
      borderColor: '#153B44'
    }],
  }
});

const paymentMethodChartData = computed(() => {
  return {
    labels: data.value?.expenses_by_payment_method.map((v) => v.payment_method),
    datasets: [{
      data: data.value ? data.value.expenses_by_payment_method.map((v) => v.total_value) : [],
      backgroundColor: data.value ? calculateBackgroundColor(data.value.expenses_by_payment_method) : [],
      borderColor: '#153B44'
    }],
  }
});

const paymnentSourceChartData = computed(() => {
  return {
    labels: data.value?.expenses_by_payment_source.map((v) => v.payment_source),
    datasets: [{
      data: data.value ? data.value.expenses_by_payment_source.map((v) => v.total_value) : [],
      backgroundColor: data.value ? calculateBackgroundColor(data.value.expenses_by_payment_source) : [],
      borderColor: '#153B44'
    }],
  }
});
</script>

<style scoped>
.chart {
  height: 400px;
}
</style>
