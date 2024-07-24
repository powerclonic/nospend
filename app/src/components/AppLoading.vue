<template>
  <v-overlay
    persistent
    v-model="loading"
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
import auth from "@/services/api/auth";

const loading = ref(true);

try {
  await auth.check();
  useRouter().push("/home");
} catch (error) {
  //
} finally {
  loading.value = false;
}
</script>
