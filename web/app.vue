<script setup lang="ts">
  const sectionTitle = ref('');
  provide('setTitle', (title: string) => (sectionTitle.value = title));
</script>
<template>
  <header
    class="d-flex align-items-center gap-5 mx-auto border-bottom border-3">
    <NuxtLink to="/">
      <MainLogo />
    </NuxtLink>
    <input
      type="text"
      class="form-control w-75"
      placeholder="Pesquisar..."
      aria-label="Pesquisar..." />
  </header>
  <section class="py-4 px-5 d-flex flex-column">
    <NuxtPage />
  </section>
</template>
