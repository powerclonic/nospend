<template>
  <v-form @submit.prevent="sendForm" v-model="form" :disabled="loading">
    <h1 class="text-h6 text-secondary text-center mb-3">
      Já nos conhecemos, né?
    </h1>
    <div class="d-flex flex-column ga-2">
      <v-text-field
        v-model="credentials.email"
        label="E-mail"
        color="primary"
        type="email"
        autocomplete="email"
        :rules="[rules.required]"
        hide-details="auto"
      />
      <v-text-field
        v-model="credentials.password"
        label="Senha"
        color="primary"
        type="password"
        autocomplete="current-password"
        :rules="[rules.required]"
        hide-details="auto"
      />
      <v-alert
        v-model="signinError"
        closable
        type="error"
        variant="tonal"
        text="Credenciais inválidas"
      />
      <v-btn
        color="primary"
        variant="tonal"
        block
        type="submit"
        :disabled="!form"
        :loading
      >
        ENTRAR
      </v-btn>
    </div>
  </v-form>
  <v-btn color="secondary" variant="text" to="/signup">
    AINDA NÃO SOU CADASTRADO
  </v-btn>
</template>

<script setup lang="ts">
import auth from "@/services/api/auth";
import { SignInData } from "@/types";

const router = useRouter();

const form = ref(false);
const loading = ref(false);
const signinError = ref(false);

const credentials: Ref<SignInData> = ref({
  email: "",
  password: "",
});

const rules = {
  required: (value: string) => !!value || "Campo obrigatório",
};

const sendForm = async () => {
  try {
    signinError.value = false;
    loading.value = true;

    await auth.signin(credentials.value);

    router.push("/home");
  } catch (error) {
    signinError.value = true;
  } finally {
    loading.value = false;
  }
};
</script>
