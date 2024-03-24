<script setup lang="ts">
  import { required } from '@vuelidate/validators';

  const {
    params: { id },
  } = useRoute();
  const rules = {
    ...getValidationRules(),
    public_id: { required },
  };
  const state = reactive({ ...(await useProduct(id as string)), image: null });
  const handleSubmit = async (formData: FormData) => {
    formData.append('public_id', state.public_id);
    if (!state.image) {
      formData.delete('image');
    }
    // https://stackoverflow.com/a/60781688
    await $fetch('/api/products/' + id + '?_method=PUT', {
      method: 'POST',
      body: formData,
      onResponse: async () => {
        await navigateTo('/product/' + id);
      },
      onResponseError: ({ response: { _data } }) => handleError(_data),
    });
  };
</script>
<template>
  <PageHeader title="Editar Produto" />
  <section class="w-50 d-flex m-auto">
    <EditProductForm
      :rules="rules"
      :state="state"
      :handle-submit="handleSubmit">
      <template v-slot:before-fields>
        <FormField
          id="public_id"
          label="Código"
          name="public_id"
          disabled />
      </template>
    </EditProductForm>
  </section>
</template>
