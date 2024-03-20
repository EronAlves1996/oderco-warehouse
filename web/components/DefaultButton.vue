<script setup lang="ts">
  const { classNames } = defineProps<{
    classNames?: string;
    type?: 'button' | 'submit';
    outline?: boolean;
  }>();
</script>
<template>
  <button
    :type="type ? type : 'button'"
    :class="
      'btn py-2 px-4 fw-bold ' +
      (classNames ? classNames : '') +
      ' ' +
      (outline ? 'btn-outline-primary' : 'btn-primary')
    ">
    <slot />
  </button>
</template>
