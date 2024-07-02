<template>
  <div class="d-flex justify-space-between align-center">
    <v-btn @click="subMonth" icon variant="text" color="primary">
      <v-icon>mdi-chevron-left</v-icon>
    </v-btn>
    <p class="text-h6 text-secondary">{{ formattedDate }}</p>
    <v-btn @click="addMonth" icon variant="text" color="primary">
      <v-icon>mdi-chevron-right</v-icon>
    </v-btn>
  </div>
</template>

<script setup lang="ts">
import { useDate } from "vuetify";

const date = defineModel({
  default: new Date(),
});

const adapter = useDate();

const addMonth = () => (date.value = adapter.addMonths(date.value, 1) as Date);
const subMonth = () => (date.value = adapter.addMonths(date.value, -1) as Date);

const formattedDate = computed(() => {
  const month = date.value.toLocaleDateString("pt-BR", { month: "long" });
  const year = date.value.getFullYear();

  return `${month}/${year}`;
});
</script>
