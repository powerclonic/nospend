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
        </div>
      </template>
    </v-card>
    <card-button
      title="Todas as despesas"
      icon="mdi-chevron-right"
      @click="$router.push('/expenses')"
    />
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
</script>
