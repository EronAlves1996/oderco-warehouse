<script setup lang="ts">
import {
  required,
  numeric,
  minValue,
  integer,
  maxLength,
} from "@vuelidate/validators";

const state = reactive({
  name: "",
  quantity: 0,
  price: 0,
});
const rules = {
  name: { required, maxLength: maxLength(100) },
  quantity: { required, numeric, minValue: minValue(0), integer },
  price: { required, numeric, minValue: minValue(0) },
};
</script>
<template>
  <PageHeader title="Novo Produto" />
  <section class="w-50 d-flex m-auto">
    <FormContext :state="state" :rules="rules">
      <div class="d-flex flex-column">
        <FormField id="name" label="Nome" type="text" name="name" />
        <FormField
          id="quantity"
          label="Quantidade de Estoque"
          type="number"
          name="quantity"
        />
        <FormField id="price" label="Preço" type="number" name="price" />
      </div>
      <DefaultButton class-names="w-100">Finalizar</DefaultButton>
    </FormContext>
  </section>
</template>
