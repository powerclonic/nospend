<template>
  <v-dialog :model-value="loading" persistent>
    <v-card color="accent">
      <template #title>Buscando seus dados</template>
      <template #append>
        <v-progress-circular color="primary" indeterminate />
      </template>
    </v-card>
  </v-dialog>
  <v-container class="d-flex flex-column ga-2">
    <month-picker v-model="selectedDate" />
    <card-button title="Nova despesa" icon="mdi-plus">
      <new-expense />
    </card-button>
    <v-card class="rounded-lg" color="accent">
      <template #title> Despesas do mês </template>
      <template #append>
        <v-icon icon="mdi-calendar" color="primary" />
      </template>
      <template #text>
        <div class="d-flex flex-column ga-2" v-if="data && data.length > 0">
          <expense-card v-for="(expense, key) in data" :key :expense detailed />
        </div>
        <v-card
          v-else
          title="Nada aqui"
          subtitle="Nenhuma despesa para esse mês"
          color="background"
        >
          <template #append>
            <v-icon icon="mdi-clipboard-check-outline" color="primary" />
          </template>
        </v-card>
      </template>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import user from "@/services/api/user";
import { Expense } from "@/types";

const loading = ref(false);

const selectedDate = ref(new Date());
const data: Ref<Array<Expense> | null> = ref(null);

const getData = async () => {
  try {
    loading.value = true;

    data.value = (
      await user.expenses(
        selectedDate.value.getMonth() + 1,
        selectedDate.value.getFullYear()
      )
    ).data.data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

watch(selectedDate, () => getData());

getData();
</script>
