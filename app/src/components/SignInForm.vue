<template>
  <v-form>
    <h1 class="text-h6 text-secondary text-center mb-3">
      Já nos conhecemos, né?
    </h1>
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
      autocomplete="current-password"
    />
    <v-btn color="primary" variant="tonal" block>ENTRAR</v-btn>
  </v-form>
  <v-btn color="secondary" variant="text" @click="$router.push('/signup')"
    >AINDA NÃO SOU CADASTRADO</v-btn
  >
</template>
