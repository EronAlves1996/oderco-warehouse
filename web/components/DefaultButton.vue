<script setup lang="ts">
const { classNames } = defineProps<{
  classNames?: string;
  type?: "button" | "submit";
}>();
</script>
<template>
  <button
    :type="type ? type : 'button'"
    :class="
      'btn btn-primary py-2 px-4 fw-bold ' + (classNames ? classNames : '')
    "
  >
    <slot />
  </button>
</template>
