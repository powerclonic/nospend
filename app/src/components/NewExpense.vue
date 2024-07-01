<template>
  <v-dialog v-model="showDialog" persistent :activator>
    <v-card class="rounded-lg" color="background">
      <template #title> {{ update ? "ATUALIZAR" : "CRIAR" }} DESPESA </template>
      <template #append>
        <v-btn icon variant="text" @click="showDialog = false">
          <v-icon icon="mdi-close" />
        </v-btn>
      </template>
      <template #text>
        <v-form>
          <v-text-field
            v-model="expenseInput.name"
            label="Nome da despesa"
            color="primary"
            type="text"
            placeholder="Conta X"
          />
          <v-text-field
            v-model="expenseInput.due_date"
            label="Data de vcto./pgto."
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
            v-model="expenseInput.payment_method"
            label="Forma de pagamento"
            color="primary"
            hint="Opcional"
            placeholder="Cartão Y"
          />
          <v-text-field
            v-model="expenseInput.payment_source"
            label="Fonte de pagamento"
            color="primary"
            hint="Opcional"
            placeholder="Banco Z"
          />
          <v-text-field
            v-model="expenseInput.category"
            label="Categoria"
            color="primary"
          />
          <v-switch
            v-model="expenseInput.recurrent"
            label="Recorrente"
            color="primary"
            hide-details
          />
          <v-switch
            v-model="expenseInput.auto_pay"
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
import { Expense } from "@/types";
import { PropType } from "vue";

const props = defineProps({
  update: {
    type: Boolean,
    default: false,
  },
  expense: {
    type: Object as PropType<Expense | null>,
    default: null,
  },
  activator: {
    default: "parent",
  },
});

const showDialog = ref(false);

const expenseInput: Ref<any> = ref({
  name: "",
  value: 0,
  payment_method: "",
  payment_source: "",
  due_date: new Date().toISOString().split("T")[0],
  category: "",
  recurrent: false,
  auto_pay: false,
});

if (props.update) {
  expenseInput.value = { ...expenseInput.value, ...props.expense };
  expenseInput.value.value *= 100;
}

const formattedValue = computed({
  get: () => {
    return new Intl.NumberFormat("pt-BR", { minimumFractionDigits: 2 }).format(
      expenseInput.value.value / 100
    );
  },
  set: (newValue: string) => {
    expenseInput.value.value = Number(newValue.replace(/\D/g, ""));
  },
});
</script>
