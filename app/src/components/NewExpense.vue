<template>
  <v-dialog :model-value="loading" persistent>
    <v-card color="accent">
      <template #title
        >{{ update ? "Atualizando" : "Criando" }} despesa</template
      >
      <template #append>
        <v-progress-circular color="primary" indeterminate />
      </template>
    </v-card>
  </v-dialog>
  <v-dialog v-model="showDialog" persistent :activator>
    <v-form @submit.prevent="sendForm">
      <v-card class="rounded-lg" color="background">
        <template #title>
          {{ update ? "ATUALIZAR" : "CRIAR" }} DESPESA
        </template>
        <template #append>
          <v-btn icon variant="text" @click="showDialog = false">
            <v-icon icon="mdi-close" />
          </v-btn>
        </template>
        <template #text>
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
          <button class="d-none" ref="formButton" type="submit">w</button>
        </template>
        <template #actions>
          <v-btn color="error" variant="text" @click="showDialog = false">
            Cancelar
          </v-btn>
          <v-btn color="primary" variant="tonal" type="submit"> Salvar </v-btn>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import expenseApi from "@/services/api/expense";
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

const emits = defineEmits(["updated"]);

const formButton = ref(null);

const showDialog = ref(false);
const loading = ref(false);

const cleanInput = {
  name: "",
  value: 0,
  payment_method: "",
  payment_source: "",
  due_date: new Date().toISOString().split("T")[0],
  category: "",
  recurrent: false,
  auto_pay: false,
};

const getFormattedDatte = (date: string) => {
  const dateArray = date.split("/");

  return `${dateArray[2]}-${dateArray[1]}-${dateArray[0]}`;
};

const expenseInput: Ref<any> = ref({ ...cleanInput });

if (props.update) {
  expenseInput.value = {
    ...expenseInput.value,
    ...props.expense,
    due_date: getFormattedDatte(props.expense?.due_date as unknown as string),
  };
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

const sendForm = async () => {
  try {
    loading.value = true;
    if (props.update) {
      await expenseApi.update(expenseInput.value);
    } else {
      await expenseApi.create(expenseInput.value);
    }
    expenseInput.value = { ...cleanInput };
    showDialog.value = false;

    emits("updated");
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};
</script>
