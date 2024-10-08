<template>
  <v-card color="background">
    <expense-details :expense @updated="$emit('updated')" />
    <template #title>
      <span class="text-light">{{ expense.name }}</span>
    </template>
    <template #subtitle>
      <span class="font-weight-bold">{{ maskedValue }}</span>
    </template>
    <template #append>
      <v-icon icon="mdi-credit-card" color="primary" />
    </template>
    <template #text v-if="detailed">
      <span class="d-flex flex-wrap ga-2">
        <v-chip prepend-icon="mdi-circle" :color="status.color">
          {{ status.text }}
        </v-chip>
        <v-chip prepend-icon="mdi-calendar" color="secondary">
          {{ expense.due_date }}
        </v-chip>
        <v-chip prepend-icon="mdi-repeat" color="secondary">
          {{ expense.recurrent ? "Sim" : "Não" }}
        </v-chip>
        <v-chip
          v-show="expense.payment_method"
          prepend-icon="mdi-cash"
          color="secondary"
        >
          {{ expense.payment_method }}
        </v-chip>
        <v-chip
          v-show="expense.payment_source"
          prepend-icon="mdi-bank"
          color="secondary"
        >
          {{ expense.payment_source }}
        </v-chip>
      </span>
    </template>
  </v-card>
</template>

<script setup lang="ts">
import type { Expense } from "@/types";
import { PropType } from "vue";

const props = defineProps({
  detailed: {
    type: Boolean,
    default: false,
  },
  expense: {
    type: Object as PropType<Expense>,
    required: true,
  },
});

defineEmits(["updated"]);

const formatter = new Intl.NumberFormat("pt-BR", {
  style: "currency",
  currency: "BRL",
});

const maskedValue = computed(() => formatter.format(props.expense.value));

const statusList = {
  PAID: {
    color: "primary",
    text: "Paga",
  },
  AWAITING_PAYMENT: {
    color: "warning",
    text: "Não paga",
  },
  EXPIRED: {
    color: "error",
    text: "Vencida",
  },
};

const status = computed(() => statusList[props.expense.status]);
</script>
