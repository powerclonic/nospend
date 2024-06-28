<template>
  <v-card color="accent" class="rounded-lg" link>
    <template #title> {{ title }} </template>
    <template #append>
      <v-icon :icon="icon" color="primary" />
    </template>
    <slot />
  </v-card>
</template>

<script setup lang="ts">
defineProps({
  title: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
});
</script>
