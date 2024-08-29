<template>
  <v-form
    v-model="form"
    validate-on="lazy"
    @submit.prevent="sendForm"
    :disabled="loading"
  >
    <h1 class="text-h6 text-secondary text-center mb-3">
      Redefinição de senha
    </h1>
    <div class="d-flex flex-column ga-2">
      <v-text-field
        v-model="data.password"
        label="Senha"
        color="primary"
        type="password"
        autocomplete="new-password"
        :rules="[rules.required, rules.password]"
        hint="A senha deve conter no mínimo 8 caracteres, sendo eles letras maiúsculas e minúsculas, números e símbolos"
        hide-details="auto"
      />
      <v-text-field
        v-model="data.password_confirmation"
        label="Confirmar senha"
        color="primary"
        type="password"
        autocomplete="new-password"
        :rules="[rules.required, rules.equals]"
        hide-details="auto"
      />
      <v-btn
        color="primary"
        variant="tonal"
        block
        type="submit"
        :disabled="!form"
        :loading
      >
        REDEFINIR
      </v-btn>
    </div>
  </v-form>
  <v-btn color="secondary" variant="text" @click="$router.push('/')">
    PÁGINA INICIAL
  </v-btn>
</template>

<script setup lang="ts">
import auth from "@/services/api/auth";
import { useAppStore } from "@/stores/app";
const route = useRoute();
const router = useRouter();
const store = useAppStore();

const form = ref(false);
const loading = ref(false);

const passwordRegExp = new RegExp(
  /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})/
);

const data = ref({
  email: route.query.email,
  // @ts-expect-error
  token: route.params.token,
  password: "",
  password_confirmation: "",
});

const rules = {
  required: (value: string) => !!value || "Campo obrigatório",
  password: (value: string) =>
    !!passwordRegExp.test(value) || "Formato de senha inválido",
  equals: (value: string) =>
    value === data.value.password || "As senhas devem ser iguais",
};

const sendForm = async () => {
  try {
    loading.value = true;

    await auth.resetPassword(data.value);
    store.setMessage("Senha redefinida com sucesso!", "success");

    router.push("/");
  } catch (error: any) {
    store.setMessage(error.response.data.message, "error");
  } finally {
    loading.value = false;
  }
};
</script>
