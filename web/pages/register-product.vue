<script setup lang="ts">
  import {
    required,
    numeric,
    minValue,
    integer,
    maxLength,
    decimal,
    helpers,
  } from '@vuelidate/validators';
  import { object, z } from 'zod';
  const { withMessage } = helpers;

  const state = reactive({
    name: '',
    quantity: 0,
    price: 0,
    image: null,
  });
  const requiredWithMessage = withMessage('Campo é obrigatório', required);
  const numericWithMessage = withMessage('Permitido apenas números!', numeric);
  const nonNegative = withMessage('Campo não pode ser negativo!', minValue(0));
  const imageValidator = (f: File) =>
    f === null ||
    f === undefined ||
    ['jpg', 'png'].some((format) => f.type.endsWith(format));

  const rules = {
    name: {
      requiredWithMessage,
      maxLength: withMessage(
        'Campo suporta até 100 caracteres',
        maxLength(100),
      ),
    },
    quantity: {
      requiredWithMessage,
      numericWithMessage,
      nonNegative,
      integer: withMessage('Campo não pode ser decimal!', integer),
    },
    price: {
      requiredWithMessage,
      numericWithMessage,
      nonNegative,
      decimal: withMessage('Obrigatório que campo seja decimal!', decimal),
    },
    image: {
      imageValidator: withMessage(
        'Necessário que imagem seja nos formatos png ou jpg',
        imageValidator,
      ),
    },
  };

  const handleSubmit = async (formData: FormData) => {
    await $fetch('/api/products/', {
      method: 'POST',
      body: formData,
      onResponse: (ctx) => console.log(ctx),
      onResponseError: ({ response: { _data } }) => {
        const errorDetailsSchema = z.object({
          detail: z.string().or(z.record(z.array(z.string()))),
          instance: z.string(),
          status: z.number().int(),
          title: z.string(),
          type: z.string(),
        });
        const { detail } = errorDetailsSchema.parse(_data);
        if (typeof detail === 'object') {
          Object.values(detail).forEach((v) =>
            v.forEach((e) => useToast().error(e)),
          );
          return;
        }

        if (typeof detail === 'string') {
          useToast().error(detail);
        }
      },
      onRequestError: (ctx) => console.log(ctx),
    });
  };
</script>
<template>
  <PageHeader title="Novo Produto" />
  <section class="w-50 d-flex m-auto">
    <FormContext
      :state="state"
      :rules="rules"
      :handle-submit="handleSubmit">
      <div class="d-flex flex-column">
        <FormField
          id="name"
          label="Nome"
          name="name" />
        <FormField
          id="quantity"
          label="Quantidade de Estoque"
          name="quantity" />
        <FormField
          id="price"
          label="Preço"
          name="price" />
        <ImageUploadField
          label="Imagem"
          id="image"
          name="image" />
      </div>
      <DefaultButton
        class-names="w-100"
        type="submit"
        >Finalizar</DefaultButton
      >
    </FormContext>
  </section>
</template>
