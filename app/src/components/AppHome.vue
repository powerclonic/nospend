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
          <v-card v-if="data.today_expenses.length <= 0" title="Tudo tranquilo"
            subtitle="Nenhuma despesa para o dia de hoje" color="background">
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
          <expense-stat title="Total em despesas" :value="data.month_statistics.expenses_total_value" icon="mdi-wallet"
            monetary />
          <expense-stat title="Total já pago" :value="data.month_statistics.expenses_total_paid" icon="mdi-currency-usd"
            monetary />
          <expense-stat title="Total não pago" :value="data.month_statistics.expenses_total_unpaid"
            icon="mdi-currency-usd-off" monetary />
          <expense-stat title="Despesas não recorrentes" :value="data.month_statistics.expenses_total_not_recurrent"
            icon="mdi-sync-alert" monetary />
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
          <v-card v-if="total_recurrent && total_not_recurrent" color="background">
            <template #title>
              Recorrência
            </template>
            <template #subtitle>
                Recorrentes vs Não Recorrentes
            </template>
            <template #append>
              <v-icon icon="mdi-repeat" color="primary" />
            </template>
            <template #text>
              <doughnut :data="recurrencyChartData" />
            </template>
          </v-card>
          <v-card v-else title="Sem despesas" subtitle="Não há dados para mostrar" color="background">
            <template #append>
              <v-icon icon="mdi-currency-usd-off" color="primary" />
            </template>
          </v-card>
          <v-card v-if="total_recurrent && total_not_recurrent" color="background">
            <template #title>
              Categorias
            </template>
            <template #subtitle>
                Valor total por categoria
            </template>
            <template #append>
              <v-icon icon="mdi-tag" color="primary" />
            </template>
            <template #text>
              <doughnut :data="categoryChartData" />
            </template>
          </v-card>
          <v-card v-else title="Sem despesas" subtitle="Não há dados para mostrar" color="background">
            <template #append>
              <v-icon icon="mdi-currency-usd-off" color="primary" />
            </template>
          </v-card>
        </div>
      </template>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  ArcElement,
  ChartData
} from 'chart.js'

import { Doughnut } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, ArcElement, Title, Tooltip, Legend)

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

const total_recurrent = computed(() => {
  if (data.value) {
    return data.value.month_statistics.expenses_total_value - data.value.month_statistics.expenses_total_not_recurrent
  }

  return 0
})

const total_not_recurrent = computed(() => {
  if (data.value) {
    return data.value.month_statistics.expenses_total_not_recurrent
  }

  return 0
})

ChartJS.overrides['doughnut'].plugins.legend.labels.color = '#FFFFFF'
ChartJS.overrides['doughnut'].plugins.legend.labels.boxWidth = 12
ChartJS.overrides['doughnut'].plugins.legend.labels.boxHeight = 12

const recurrencyChartData: ComputedRef<ChartData<"doughnut", number[], unknown>>= computed(() => {
  return {
    labels: [
      'Recorrente',
      'Não Recorrente',
    ],
    datasets: [{
      data: [
        total_recurrent.value,
        total_not_recurrent.value
      ],
      backgroundColor: [
        '#849c02',
        '#2D6E7E'
      ],
      borderColor: '#153B44'
    }],
  }
});


const categoryChartData: ComputedRef<ChartData<"doughnut", number[], unknown>>= computed(() => {
  return {
    labels: data.value?.expenses_by_category.map((v) => v.category),
    datasets: [{
      data: data.value ? data.value.expenses_by_category.map((v) => v.total_value) : [],
      backgroundColor: data.value ? data.value.expenses_by_category.map(() => {
        const randomDarkColor = () => {
          const r = Math.floor(Math.random() * 192); // Limit to darker tones
          const g = Math.floor(Math.random() * 192);
          const b = Math.floor(Math.random() * 192);
          return `rgb(${r}, ${g}, ${b})`;
        };
        return randomDarkColor();
      }) : [],
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
