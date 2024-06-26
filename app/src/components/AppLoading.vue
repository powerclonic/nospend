<template>
  <v-overlay
    persistent
    v-model="active"
    class="bg-background d-flex flex-column align-center justify-center text-center"
    :scrim="false"
    transition="fade"
  >
    <h1 class="text-primary text-h1 font-weight-black">nospend</h1>
    <p class="text-secondary text-h6 mb-8">power up your wallet.</p>
    <v-progress-circular indeterminate color="primary" size="large" />
  </v-overlay>
</template>

<script lang="ts" setup>
const active = ref(true);

setTimeout(() => {
  active.value = false;
}, 2500);
</script>
