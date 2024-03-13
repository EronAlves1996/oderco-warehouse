<script setup lang="ts">
import useVuelidate from "@vuelidate/core";

const { state, rules, handleSubmit } = defineProps<{
  state: Record<string, any>;
  rules: Record<string, any>;
  handleSubmit: (values: FormData) => void;
}>();

const v$ = useVuelidate(rules, state);
provide("formControls", { state, v$ });
const handleSubmitInternal = async (submitEvent: Event) => {
  submitEvent.preventDefault();
  const isFormCorrect = await v$.value.$validate();
  if (isFormCorrect)
    handleSubmit(new FormData(submitEvent.target as HTMLFormElement));
};
</script>
<template>
  <form class="w-100 d-flex flex-column gap-5" @submit="handleSubmitInternal">
    <slot />
  </form>
</template>
