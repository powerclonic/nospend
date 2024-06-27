<template>
  <v-card color="background">
    <template #title>
      <span class="text-light">{{ title }}</span>
    </template>
    <template #subtitle>
      <span class="font-weight-bold">{{ monetary ? maskedValue : value }}</span>
    </template>
    <template #append>
      <v-icon :icon color="primary" />
    </template>
  </v-card>
</template>

<script setup lang="ts">
const props = defineProps({
  monetary: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
});

const formatter = new Intl.NumberFormat("pt-BR", {
  style: "currency",
  currency: "BRL",
});

const maskedValue = computed(() =>
  formatter.format(props.monetary ? (props.value as number) : 0)
);
</script>
