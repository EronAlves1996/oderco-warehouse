<script setup lang="ts">
const { title, renderAddProductButton } = defineProps({
  title: { type: String, required: true },
  renderAddProductButton: { type: Boolean, default: false },
});
</script>
<template>
  <header class="d-flex justify-content-between align-items-center gap-3">
    <h4 class="fw-bold">{{ title }}</h4>
    <NuxtLink to="/register-product" v-if="renderAddProductButton">
      <DefaultButton> + Adicionar Produto </DefaultButton>
    </NuxtLink>
  </header>
</template>
