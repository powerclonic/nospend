<template>
  <v-dialog v-model="showDialog" activator="parent" :persistent="loading">
    <v-card class="rounded-lg" color="background">
      <template #title>Recuperar conta</template>
      <template #subtitle> Iremos enviar instruções para seu e-mail. </template>
      <template #append>
        <v-icon icon="mdi-lock-alert" color="primary" />
      </template>
      <template #text>
        <v-form @submit.prevent="sendForm" :disabled="loading">
          <v-text-field
            v-model="emailAddress"
            label="E-mail da sua conta"
            color="primary"
            hide-details="auto"
            :loading
          />
        </v-form>
      </template>
      <template #actions>
        <v-btn
          color="error"
          variant="tonal"
          @click="showDialog = false"
          :disabled="loading"
        >
          CANCELAR
        </v-btn>
        <v-btn
          color="primary"
          variant="tonal"
          @click="sendForm"
          :disabled="loading || !emailAddress"
        >
          ENVIAR
        </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import auth from "@/services/api/auth";
import { useAppStore } from "@/stores/app";

const store = useAppStore();

const showDialog = ref(false);
const emailAddress = ref("");
const loading = ref(false);

const sendForm = async () => {
  try {
    loading.value = true;

    await auth.forgotPassword(emailAddress.value);
    store.setMessage("E-mail enviado com sucesso", "success");
  } catch (err: any) {
    store.setMessage(
      err.response?.data?.message ??
        "Tivemos um problema ao completar sua requisição",
      "error"
    );
  } finally {
    loading.value = false;
  }
};
</script>
