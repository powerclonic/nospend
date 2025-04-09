<template>
  <v-dialog
    :model-value="loading"
    persistent
  >
    <v-card color="accent">
      <template #title>{{ update ? "Atualizando" : "Criando" }} despesa</template>
      <template #append>
        <v-progress-circular
          color="primary"
          indeterminate
        />
      </template>
    </v-card>
  </v-dialog>
  <v-dialog
    v-model="showDialog"
    persistent
    :activator
  >
    <v-card
      ref="formCardContainer"
      class="rounded-lg"
      color="background"
    >
      <template #title>
        {{ update ? "ATUALIZAR" : "CRIAR" }} DESPESA
      </template>
      <template #append>
        <v-btn
          icon
          variant="text"
          @click="showDialog = false"
        >
          <v-icon icon="mdi-close" />
        </v-btn>
      </template>
      <template #text>
        <v-form
          validate-on="submit"
          ref="newExpenseForm"
          @submit.prevent="sendForm"
        >
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
            :disabled="isRepeatEnabled"
            v-model="expenseInput.recurrent"
            label="Repetir indefinidamente"
            color="primary"
            hide-details
          />
          <v-switch
            v-model="isRepeatEnabled"
            label="Repetir por..."
            color="primary"
            hide-details
          />
          <v-number-input
            v-if="isRepeatEnabled"
            v-model="expenseInput.repeat_for"
            control-variant="default"
            density="compact"
            :min="1"
            :max="600"
            :suffix="(expenseInput.repeat_for || 0) > 1 ? 'meses' : 'mês'"
            persistent-hint
            :hint="repeatForHint"
          />
          <v-switch
            v-model="expenseInput.auto_pay"
            label="Pagar automaticamente"
            color="primary"
            hide-details
          />
          <button
            class="d-none"
            ref="formButton"
            type="submit"
          />
        </v-form>
      </template>
      <template #actions>
        <v-btn
          color="error"
          variant="text"
          @click="showDialog = false"
        >
          Cancelar
        </v-btn>
        <v-btn
          color="primary"
          variant="tonal"
          type="submit"
          @click="formSubmitButton?.click()"
        > Salvar </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script
  setup
  lang="ts"
>
import expenseApi from "@/services/api/expense";
import { Expense, ExpenseInput, Hints } from "@/types";
import { PropType, ShallowRef, useTemplateRef } from "vue";
import { VCard, VForm } from "vuetify/components";
import { addMonths } from "date-fns";

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

const cleanInput: ExpenseInput = {
  name: "",
  value: 0,
  payment_method: undefined,
  payment_source: undefined,
  due_date: new Date().toISOString().split("T")[0],
  category: undefined,
  recurrent: false,
  repeat_for: -1,
  auto_pay: false,
};

const getFormattedDatte = (date: string) => {
  const dateArray = date.split("/");

  return `${dateArray[2]}-${dateArray[1]}-${dateArray[0]}`;
};

const isRepeatEnabled = ref(false);
const expenseInput: Ref<ExpenseInput> = ref({ ...cleanInput });

if (props.update && props.expense) {
  expenseInput.value = {
    ...expenseInput.value,
    ...props.expense,
    due_date: getFormattedDatte(props.expense.due_date as unknown as string),
  };
  expenseInput.value.value *= 100;
  isRepeatEnabled.value = props.expense.repeat_for > 0;
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
    if (props.update && props.expense) {
      await expenseApi.update(expenseInput.value, props.expense.id);
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

const getFutureDate = (date: string, months: number) => {
  return addMonths(
    new Date(`${date}T00:00:00`),
    months
  );
};

watch(isRepeatEnabled, (newValue) => {
  expenseInput.value.repeat_for = newValue ? 1 : -1;
  expenseInput.value.recurrent = newValue;
});

const repeatForHint = computed(() => {
  const repeatUntilDate = getFutureDate(
    expenseInput.value.due_date,
    expenseInput.value.repeat_for
  );

  return `Repetir até ${repeatUntilDate.toLocaleDateString('pt-BR', {
    year: "numeric",
    month: "long",
  })}`;
});
</script>
