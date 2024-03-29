<script setup lang="ts">
  import type { Validation } from '@vuelidate/core';
  import type { MaskaDetail } from 'maska';

  const { name } = defineProps<{
    label: string;
    id: string;
    name: string;
    disabled?: boolean;
  }>();

  const formControls = inject<{
    state: any;
    v$: globalThis.Ref<Validation<any, any>>;
  }>('formControls');

  const state = formControls?.state;
  const v$ = formControls?.v$;

  const innerValue = ref(String(state[name]).replace('.', ','));
  const options = {
    onMaska: (detail: MaskaDetail) =>
      (state[name] = Number(
        detail.masked.replace('R$', '').replace(',', '.').trim(),
      )),
    postProcess: (value: string) =>
      value.includes(',')
        ? (() => {
            const maskEditSplit = value.split(',');
            maskEditSplit[1] = maskEditSplit[1].padEnd(2, '0');
            return maskEditSplit.join(',');
          })()
        : (() => {
            if (value.endsWith('00')) {
              return value.replace('00', ',00');
            }
            return value.concat(',00');
          })(),
  };
</script>
<template>
  <label
    :for="id"
    class="form-label"
    >{{ label }}</label
  >
  <input
    class="form-control"
    :id="id"
    :name="name"
    v-model="innerValue"
    @blur="v$?.[name].$touch"
    :disabled="disabled"
    v-maska:[options]
    data-maska="R$ 0,99"
    data-maska-tokens="0:\d:multiple|9:\d:optional" />
  <div
    class="input-errors text-danger"
    v-for="error of v$?.[name].$errors"
    :key="error.$uid">
    <div class="error-msg">{{ error.$message }}</div>
  </div>
</template>
