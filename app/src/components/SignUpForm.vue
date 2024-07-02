<template>
  <v-form
    v-model="form"
    validate-on="lazy"
    @submit.prevent="sendForm"
    :disabled="loading"
  >
    <h1 class="text-h6 text-secondary text-center mb-3">Seja bem-vindo(a)!</h1>
    <div class="d-flex flex-column ga-2">
      <v-text-field
        v-model="user.name"
        label="Nome"
        color="primary"
        type="text"
        autocomplete="full-name"
        :rules="[rules.required]"
        hide-details="auto"
      />
      <v-text-field
        v-model="user.email"
        label="E-mail"
        color="primary"
        type="email"
        autocomplete="email"
        :rules="[rules.required, rules.email]"
        hide-details="auto"
      />
      <v-text-field
        v-model="user.password"
        label="Senha"
        color="primary"
        type="password"
        autocomplete="new-password"
        :rules="[rules.required, rules.password]"
        hint="A senha deve conter no mínimo 8 caracteres, sendo eles letras maiúsculas e minúsculas, números e símbolos"
        hide-details="auto"
      />
      <v-text-field
        v-model="user.password_confirmation"
        label="Confirmar senha"
        color="primary"
        type="password"
        autocomplete="new-password"
        :rules="[rules.required, rules.equals]"
        hide-details="auto"
      />
      <v-alert
        v-model="signupError"
        type="error"
        variant="tonal"
        :text="
          signupErrorMessage ??
          'Estamos com problemas para completar seu cadastro, tente novamente mais tarde'
        "
      />
      <v-btn
        color="primary"
        variant="tonal"
        block
        type="submit"
        :disabled="!form"
        :loading
      >
        CADASTRAR
      </v-btn>
    </div>
  </v-form>
  <v-btn color="secondary" variant="text" @click="$router.push('/signin')">
    JÁ SOU CADASTRADO
  </v-btn>
</template>

<script setup lang="ts">
import auth from "@/services/api/auth";
import { SignUpData } from "@/types";

const router = useRouter();

const form = ref(false);
const loading = ref(false);

const signupError = ref(false);
const signupErrorMessage: Ref<string | null> = ref(null);

const emailRegExp = new RegExp(/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/);
const passwordRegExp = new RegExp(
  /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})/
);

const user: Ref<SignUpData> = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const rules = {
  required: (value: string) => !!value || "Campo obrigatório",
  email: (value: string) =>
    !!emailRegExp.test(value) || "Formato de e-mail inváido",
  password: (value: string) =>
    !!passwordRegExp.test(value) || "Formato de senha inválido",
  equals: (value: string) =>
    value === user.value.password || "As senhas devem ser iguais",
};

const sendForm = async () => {
  try {
    signupError.value = false;
    signupErrorMessage.value = null;

    loading.value = true;

    await auth.signup(user.value);

    router.push("/home");
  } catch (error: any) {
    console.log(error);

    if (error.response?.status === 422) {
      signupErrorMessage.value = error.response?.data.message;
    }

    signupError.value = true;
  } finally {
    loading.value = false;
  }
};
</script>
