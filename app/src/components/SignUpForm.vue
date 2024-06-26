<template>
  <v-form>
    <h1 class="text-h6 text-secondary text-center mb-3">Seja bem-vindo(a)!</h1>
    <v-text-field
      label="Nome"
      color="primary"
      type="text"
      autocomplete="full-name"
    />
    <v-text-field
      label="E-mail"
      color="primary"
      type="email"
      autocomplete="email"
    />
    <v-text-field
      label="Senha"
      color="primary"
      type="password"
      autocomplete="new-password"
    />
    <v-text-field
      label="Confirmar senha"
      color="primary"
      type="password"
      autocomplete="new-password"
    />
    <v-btn color="primary" variant="tonal" block>CADASTRAR</v-btn>
  </v-form>
  <v-btn color="secondary" variant="text" @click="$router.push('/signin')"
    >JÁ SOU CADASTRADO</v-btn
  >
</template>
