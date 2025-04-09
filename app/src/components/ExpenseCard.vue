<template>
  <v-card color="background">
    <expense-details :expense @updated="$emit('updated')" />
    <template #title>
      <span class="text-light">{{ expense.name }}</span>
    </template>
    <template #subtitle>
      <span class="font-weight-bold">{{ maskedValue }}</span>
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
          {{ repeatText }}
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
import { addMonths } from "date-fns";
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

const getFutureDate = (date: string, months: number) => {
  date = date.split("/").reverse().join("-");

  return addMonths(
    new Date(`${date}T00:00:00`),
    months
  );
};

const repeatText = computed(() => {
  if (props.expense.recurrent) {
    if (props.expense.repeat_for === -1) {
      return "Mensal";
    }

    if (props.expense.repeat_for === 0) {
      return "Este mês";
    }

    const futureDate = getFutureDate(
      props.expense.due_date,
      props.expense.repeat_for
    );

    const formattedDate = futureDate.toLocaleDateString("pt-BR", { year: "numeric", month: "long" });

    return formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
  }

  return "Não";
});
</script>
