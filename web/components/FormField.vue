<script setup lang="ts">
import type { Validation } from "@vuelidate/core";

defineProps<{
  label: string;
  id: string;
  name: string;
}>();

const formControls = inject<{
  state: any;
  v$: globalThis.Ref<Validation<any, any>>;
}>("formControls");

const state = formControls?.state;
const v$ = formControls?.v$;
</script>
<template>
  <label :for="id" class="form-label">{{ label }}</label>
  <input
    class="form-control"
    :id="id"
    :name="name"
    v-model="state[name]"
    @blur="v$?.[name].$touch"
  />
  <div
    class="input-errors text-danger"
    v-for="error of v$?.[name].$errors"
    :key="error.$uid"
  >
    <div class="error-msg">{{ error.$message }}</div>
  </div>
</template>
