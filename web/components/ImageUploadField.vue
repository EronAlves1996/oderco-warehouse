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
    type="file"
    :id="id"
    :name="name"
    @change="
      (a) => {
        const input = a.target as HTMLInputElement;
        state[name] = input.files?.item(0);
      }
    "
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
