<script setup lang="ts">
import {
  required,
  numeric,
  minValue,
  integer,
  maxLength,
  decimal,
  helpers,
} from "@vuelidate/validators";
const { withMessage } = helpers;

const state = reactive({
  name: "",
  quantity: 0,
  price: 0,
  image: null,
});
const requiredWithMessage = withMessage("Campo é obrigatório", required);
const numericWithMessage = withMessage("Permitido apenas números!", numeric);
const nonNegative = withMessage("Campo não pode ser negativo!", minValue(0));
const imageValidator = (a: File) => {
  return a.type.endsWith("png") || a.type.endsWith("jpg");
};
const rules = {
  name: {
    requiredWithMessage,
    maxLength: withMessage("Campo suporta até 100 caracteres", maxLength(100)),
  },
  quantity: {
    requiredWithMessage,
    numericWithMessage,
    nonNegative,
    integer: withMessage("Campo não pode ser decimal!", integer),
  },
  price: {
    requiredWithMessage,
    numericWithMessage,
    nonNegative,
    decimal: withMessage("Obrigatório que campo seja decimal!", decimal),
  },
  image: {
    imageValidator: withMessage(
      "Necessário que imagem seja nos formatos png ou jpg",
      imageValidator,
    ),
  },
};
</script>
<template>
  <PageHeader title="Novo Produto" />
  <section class="w-50 d-flex m-auto">
    <FormContext :state="state" :rules="rules">
      <div class="d-flex flex-column">
        <FormField id="name" label="Nome" name="name" />
        <FormField
          id="quantity"
          label="Quantidade de Estoque"
          name="quantity"
        />
        <FormField id="price" label="Preço" name="price" />
        <ImageUploadField label="Imagem" id="image" name="image" />
      </div>
      <DefaultButton class-names="w-100">Finalizar</DefaultButton>
    </FormContext>
  </section>
</template>
