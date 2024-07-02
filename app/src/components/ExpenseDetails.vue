<template>
  <v-dialog v-model="showDialog" persistent activator="parent">
    <v-card class="rounded-lg" color="background">
      <template #title> DETALHES DA DESPESA </template>
      <template #append>
        <v-btn icon variant="text" @click="showDialog = false">
          <v-icon icon="mdi-close" />
        </v-btn>
      </template>
      <template #text>
        <expense-details-item title="Despesa" :text="expense.name" />
        <expense-details-item title="Valor" :text="maskedValue" />
        <expense-details-item
          title="Situação"
          :text="statusList[expense.status]"
        />
        <expense-details-item title="Vcto./Pgto." :text="maskedDueDate" />
        <expense-details-item
          title="Forma de pagamento"
          :text="expense.payment_method ?? 'Não informado'"
        />
        <expense-details-item
          title="Fonte do pagamento"
          :text="expense.payment_source ?? 'Não informada'"
        />
        <expense-details-item title="Adicionada em" :text="maskedCreatedDate" />
        <div class="d-flex flex-wrap ga-2">
          <v-chip size="large" prepend-icon="mdi-repeat">{{
            expense.recurrent ? "Sim" : "Não"
          }}</v-chip>
          <v-chip size="large" prepend-icon="mdi-cash-minus">{{
            expense.auto_pay ? "Sim" : "Não"
          }}</v-chip>
          <v-chip size="large" prepend-icon="mdi-tag">{{
            expense.category ?? "Nenhuma"
          }}</v-chip>
        </div>
      </template>
      <template #actions>
        <v-btn
          icon="mdi-cash"
          variant="tonal"
          color="primary"
          size="large"
          :disabled="expense.status === 'PAID'"
        />
        <v-btn
          icon="mdi-pencil"
          variant="tonal"
          color="warning"
          size="large"
          ref="editButton"
        />
        <v-btn icon="mdi-delete" variant="tonal" color="error" size="large" />
      </template>
    </v-card>
    <new-expense update :expense :activator="editButton" />
  </v-dialog>
</template>

<script setup lang="ts">
import { Expense } from "@/types";
import { PropType } from "vue";

const showDialog = ref(false);
const editButton = ref();

const props = defineProps({
  expense: {
    type: Object as PropType<Expense>,
    required: true,
  },
});

const formatter = new Intl.NumberFormat("pt-BR", {
  style: "currency",
  currency: "BRL",
});

const maskedValue = computed(() => formatter.format(props.expense.value));

const maskedDueDate = computed(() =>
  new Date(props.expense.due_date).toLocaleDateString("pt-BR")
);

const maskedCreatedDate = computed(() =>
  new Date(props.expense.created_at).toLocaleDateString("pt-BR")
);

const statusList = {
  PAID: "Paga",
  AWAITING_PAYMENT: "Não paga",
  EXPIRED: "Vencida",
};
</script>
