<template>
  <div class="mb-2">
    <p class="text-secondary text-subtitle-1 mb-n2">{{ title }}</p>
    <p class="text-light text-h5 font-weight-bold">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: {
    type: String,
    required: true,
  },
  text: {
    type: String,
    required: true,
  },
});
</script>
