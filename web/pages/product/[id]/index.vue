<script setup lang="ts">
  const {
    params: { id },
  } = useRoute();
  const { back } = useRouter();

  const product = await useProduct(id as string);
</script>
<template>
  <PageHeader title="Detalhes do Produto" />
  <section
    class="d-flex flex-column w-50 m-auto pt-5 justify-content-between gap-5">
    <div class="d-flex gap-4">
      <div
        class="border border-3 rounded"
        style="width: 373px; height: 373px">
        <img
          :src="product.picture ?? undefined"
          :alt="product.name"
          v-if="product.picture" />
      </div>
      <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-2 mt-5">
          <p>{{ product.public_id }}</p>
          <p class="fw-bold fs-5">{{ product.name }}</p>
          <p>Quantidade disponível: {{ product.quantity }}</p>
        </div>
        <p class="text-primary fs-2 text fw-bold">R$ {{ product.price }}</p>
      </div>
    </div>
    <div class="d-flex gap-4 justify-content-center w-75 mx-auto">
      <DefaultButton
        classNames="flex-grow-1"
        outline
        type="button"
        @click="back()">
        Voltar
      </DefaultButton>
      <NuxtLink
        :to="`./${id}/edit`"
        class="flex-grow-1">
        <DefaultButton
          type="button"
          classNames="w-100">
          Editar Produto
        </DefaultButton>
      </NuxtLink>
    </div>
  </section>
</template>
