<script setup lang="ts">
defineProps<{
  state: Record<string, any>;
  rules: Record<string, any>;
  handleSubmit: (formData: FormData) => Promise<void>;
}>();
</script>
<template>
  <FormContext :state="state" :rules="rules" :handle-submit="handleSubmit">
    <div class="d-flex flex-column">
      <slot name="before-fields" />
      <FormField id="name" label="Nome" name="name" />
      <FormField id="quantity" label="Quantidade de Estoque" name="quantity" />
      <MoneyInput id="price" label="Preço" name="price" />
      <ImageUploadField label="Imagem" id="image" name="image" />
    </div>
    <DefaultButton class-names="w-100" type="submit">Finalizar</DefaultButton>
  </FormContext>
</template>
