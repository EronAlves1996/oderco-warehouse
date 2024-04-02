<script setup lang="ts">
  useHead({
    title: 'Oderço - Novo Produto',
  });
  const state = reactive({
    name: '',
    quantity: 0,
    price: 0,
    image: null,
  });
  const rules = getValidationRules();

  const handleSubmit = async (formData: FormData) => {
    formData.append('price', String(state.price));
    await $fetch('/api/products/', {
      method: 'POST',
      body: formData,
      onResponse: async ({ response: { headers, ok } }) => {
        if (ok && headers.has('location')) {
          const location = headers.get('location');
          const splitted = location?.split('/');
          await navigateTo('/product/' + splitted?.[3]);
        }
      },
      onResponseError: ({ response: { _data } }) => handleError(_data),
    });
  };
</script>
<template>
  <PageHeader title="Novo Produto" />
  <section class="w-50 d-flex m-auto">
    <EditProductForm
      :state="state"
      :rules="rules"
      :handleSubmit="handleSubmit" />
  </section>
</template>
