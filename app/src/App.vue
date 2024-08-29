<template>
  <v-app>
    <v-main>
      <router-view />
      <v-snackbar v-model="alert" :text="store.message" :color="store.type" />
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import { useAppStore } from "./stores/app";

const store = useAppStore();

const alert = ref(false);

watch(
  () => store.message,
  (newValue) => {
    alert.value = Boolean(newValue);
  }
);
</script>
