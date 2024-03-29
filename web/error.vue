<script setup lang="ts">
  import type { NuxtError } from '#app';

  const props = defineProps({
    error: Object as () => NuxtError,
  });

  const handleError = () => clearError({ redirect: '/' });
</script>
<template>
  <div
    class="w-100 d-flex align-items-center justify-content-center flex-column gap-3">
    <MainLogo />
    <h1>{{ error?.statusCode }}</h1>
    <h3>{{ error?.statusMessage }}</h3>
    <DefaultButton
      @click="handleError"
      type="button">
      Voltar para página principal
    </DefaultButton>
  </div>
</template>
