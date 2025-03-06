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
    <v-card ref="formCardContainer" class="rounded-lg" color="background">
      <template #title>
        {{ update ? "ATUALIZAR" : "CRIAR" }} DESPESA
      </template>
      <template #append>
        <v-btn icon variant="text" @click="showDialog = false">
          <v-icon icon="mdi-close" />
        </v-btn>
      </template>
      <template #text>
        <v-form validate-on="submit" ref="newExpenseForm" @submit.prevent="sendForm">
          <v-text-field
            v-model="expenseInput.name"
            label="Nome da despesa"
            color="primary"
            type="text"
            placeholder="Conta X"
            :rules="defaultRules"
          />
          <v-text-field
            v-model="expenseInput.due_date"
            label="Data de vcto./pgto."
            color="primary"
            type="date"
            :rules="defaultRules"
          />
          <v-text-field
            v-model="formattedValue"
            label="Valor"
            color="primary"
            type="text"
            prefix="R$"
            :rules="defaultRules"
          />
          <v-combobox
            v-model="expenseInput.payment_method"
            :items="hintOptions.payment_method"
            label="Forma de pagamento"
            color="primary"
            hint="Opcional"
            clearable
            allow-new
            placeholder="Cartão Y"
          />
          <v-combobox
            v-model="expenseInput.payment_source"
            :items="hintOptions.payment_source"
            label="Fonte de pagamento"
            color="primary"
            hint="Opcional"
            clearable
            allow-new
            placeholder="Banco Z"
          />
          <v-combobox
            v-model="expenseInput.category"
            :items="hintOptions.category"
            label="Categoria"
            color="primary" 
            clearable
            allow-new
            placeholder="Adicionar nova categoria"
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
          <button class="d-none" ref="formButton" type="submit" />
        </v-form>
      </template>
      <template #actions>
        <v-btn color="error" variant="text" @click="showDialog = false">
          Cancelar
        </v-btn>
        <v-btn color="primary" variant="tonal" type="submit" @click="formSubmitButton?.click()">  Salvar </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import expenseApi from "@/services/api/expense";
import { Expense, Hints } from "@/types";
import { PropType, ShallowRef, useTemplateRef } from "vue";
import { VCard, VForm } from "vuetify/components";

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

const defaultRules = [(v: string) => !!v || "Campo obrigatório"];

const emits = defineEmits(["updated"]);


const showDialog = ref(false);
const loading = ref(false);

const hintOptions: Ref<Hints> = ref({} as Hints);
  
const formCardContainer: Readonly<ShallowRef<VCard>> = useTemplateRef("formCardContainer")! as Readonly<ShallowRef<VCard>>;
const formSubmitButton: Readonly<ShallowRef<HTMLButtonElement>> = useTemplateRef("formButton")! as Readonly<ShallowRef<HTMLButtonElement>>;
const newExpenseForm: Readonly<ShallowRef<VForm>> = useTemplateRef("newExpenseForm")! as Readonly<ShallowRef<VForm>>;

const cleanInput = {
  name: "",
  value: 0,
  payment_method: null,
  payment_source: null,
  due_date: new Date().toISOString().split("T")[0],
  category: null,
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

const loadHints = async () => { 
  let response = await expenseApi.hints();
  hintOptions.value = response.data;
}

const sendForm = async () => {
  let isFormValid = (await newExpenseForm.value?.validate());

  if (!isFormValid?.valid) {
    if (formCardContainer.value) {
      formCardContainer.value.$el.scrollTop = 0;
    }
    return;
  }

  try {
    loading.value = true;
    if (props.update) {
      await expenseApi.update(expenseInput.value);
    } else {
      await expenseApi.create(expenseInput.value);
    }
    expenseInput.value = { ...cleanInput };
    showDialog.value = false;

    loadHints();

    emits("updated");
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

loadHints();
</script>
