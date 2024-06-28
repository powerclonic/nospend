<template>
  <v-app-bar
    :elevation="2"
    class="bg-accent text-primary"
    scroll-behavior="hide"
  >
    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="drawer = !drawer" />
    </template>

    <v-app-bar-title>{{ $route.name }}</v-app-bar-title>
  </v-app-bar>
  <v-navigation-drawer v-model="drawer" color="accent">
    <v-list density="compact">
      <v-list-item title="NoSpend" subtitle="Power up your wallet." />
      <v-list-item
        prepend-icon="mdi-home"
        title="Início"
        link
        @click="$router.push('/home')"
      />
      <v-list-item
        prepend-icon="mdi-view-list"
        title="Despesas"
        link
        @click="$router.push('/expenses')"
      />
    </v-list>
    <template #append>
      <v-list density="comfortable">
        <v-list-item prepend-icon="mdi-logout" title="Sair" link />
      </v-list>
    </template>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
const drawer = ref(false);
</script>
