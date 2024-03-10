<script setup lang="ts">
const { classNames } = defineProps<{ classNames?: string }>();
</script>
<template>
  <button
    type="button"
    :class="
      'btn btn-primary py-2 px-4 fw-bold ' + (classNames ? classNames : '')
    "
  >
    <slot />
  </button>
</template>
