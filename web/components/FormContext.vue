<script setup lang="ts">
import useVuelidate from "@vuelidate/core";

const { state, rules } = defineProps(["state", "rules"]);
const v$ = useVuelidate(rules, state);
provide("formControls", { state, v$ });
</script>
<template>
  <form class="w-100 d-flex flex-column gap-5"><slot /></form>
</template>
