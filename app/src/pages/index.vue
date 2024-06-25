<template>
  <v-fade-transition>
    <app-loading v-if="loading" />
    <app-welcome v-else />
  </v-fade-transition>
</template>

<script lang="ts" setup>
const loading: Ref<boolean> = ref(true);

setTimeout(() => {
  loading.value = false;
}, 2500);
</script>
