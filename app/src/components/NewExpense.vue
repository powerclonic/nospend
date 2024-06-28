<template>
  <v-dialog v-model="showDialog" persistent activator="parent">
    <v-card class="rounded-lg" color="background">
      <template #title> NOVA DESPESA </template>
      <template #append>
        <v-btn icon variant="text" @click="showDialog = false">
          <v-icon icon="mdi-close" />
        </v-btn>
      </template>
      <template #text>
        <v-form>
          <v-text-field
            v-model="expense.name"
            label="Nome da despesa"
            color="primary"
            type="text"
            placeholder="Conta X"
          />
          <v-text-field
            v-model="expense.due_date"
            label="Data de vencimento/pagamento"
            color="primary"
            type="date"
          />
          <v-text-field
            v-model="formattedValue"
            label="Valor"
            color="primary"
            type="text"
            prefix="R$"
          />
          <v-text-field
            v-model="expense.payment_method"
            label="Forma de pagamento"
            color="primary"
            hint="Opcional"
            placeholder="Cartão Y"
          />
          <v-text-field
            v-model="expense.payment_source"
            label="Fonte de pagamento"
            color="primary"
            hint="Opcional"
            placeholder="Banco Z"
          />
          <v-text-field
            v-model="expense.category"
            label="Categoria"
            color="primary"
          />
          <v-switch
            v-model="expense.recurrent"
            label="Recorrente"
            color="primary"
            hide-details
          />
          <v-switch
            v-model="expense.auto_pay"
            label="Pagar automaticamente"
            color="primary"
            hide-details
          />
        </v-form>
      </template>
      <template #actions>
        <v-btn color="error" variant="text" @click="showDialog = false">
          Cancelar
        </v-btn>
        <v-btn color="primary" variant="tonal"> Salvar </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
const showDialog = ref(false);

const expense = ref({
  name: "",
  value: 0,
  payment_method: "",
  payment_source: "",
  due_date: new Date().toISOString().split("T")[0],
  category: "",
  recurrent: false,
  auto_pay: false,
});

const formattedValue = computed({
  get: () => {
    return new Intl.NumberFormat("pt-BR", { minimumFractionDigits: 2 }).format(
      expense.value.value / 100
    );
  },
  set: (newValue: string) => {
    expense.value.value = Number(newValue.replace(/\D/g, ""));
  },
});
</script>
