<template>
  <v-container class="d-flex flex-column ga-2">
    <month-picker />
    <card-button title="Nova despesa" icon="mdi-plus">
      <new-expense />
    </card-button>
    <v-card class="rounded-lg" color="accent">
      <template #title> Despesas do mês </template>
      <template #append>
        <v-icon icon="mdi-calendar" color="primary" />
      </template>
      <template #text>
        <div class="d-flex flex-column ga-2">
          <expense-card v-for="(expense, key) in data" :key :expense detailed />
        </div>
      </template>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { Expense } from "@/types";

const data: Array<Expense> = [
  {
    id: 1,
    value: 100,
    name: "Fatura",
    due_date: "2024-06-12",
    status: "NOT_PAID",
    created_at: "2024-06-10",
    recurrent: true,
  },
  {
    id: 1,
    value: 100,
    name: "Fatura",
    due_date: "2024-06-12",
    status: "NOT_PAID",
    created_at: "2024-06-10",
    recurrent: true,
  },
  {
    id: 1,
    value: 100,
    name: "Fatura",
    due_date: "2024-06-12",
    status: "NOT_PAID",
    created_at: "2024-06-10",
    recurrent: true,
  },
];
</script>
