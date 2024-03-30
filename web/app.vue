<script setup lang="ts">
  const route = useRoute();
  const { push } = useRouter();
  const search = ref(route.query.s ?? '');
  const debounceTimeout = ref<any>(null);
  watch(search, () => {
    clearTimeout(debounceTimeout.value);
    debounceTimeout.value = setTimeout(() => {
      push({
        path: '.',
        query: {
          ...route.query,
          s: search.value,
        },
      });
    }, 1000);
  });
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
      aria-label="Pesquisar..."
      v-model="search" />
  </header>
  <section class="py-4 px-5 d-flex flex-column">
    <NuxtPage />
  </section>
</template>
