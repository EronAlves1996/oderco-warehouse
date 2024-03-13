<script setup lang="ts">
import { z } from "zod";

const defineTitle = inject<(title: string) => void>("setTitle");
if (defineTitle) {
  defineTitle("Lista de Produtos Cadastrados");
}

const {
  data: { value },
  error,
} = await useFetch("/api/products", { server: true });

const productSchema = z.object({
  public_id: z.string().uuid(),
  name: z.string(),
  quantity: z.number().nonnegative().int(),
  picture_path: z.nullable(z.string()),
  price: z.number(),
  created_at: z.string().datetime(),
  updated_at: z.string().datetime(),
});

const productsApiSchema = z.array(productSchema);
const products = productsApiSchema.parse(value);
</script>
<template>
  <PageHeader
    title="Lista de Produtos Cadastrados"
    :render-add-product-button="true"
  />
  <section class="mt-5">
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="text-secondary">Imagem</th>
          <th class="text-secondary w-25">Código</th>
          <th colspan="2" class="text-secondary">Nome</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.public_id">
          <td>
            <img :src="p.picture_path ?? undefined" />
          </td>
          <td>{{ p.public_id }}</td>
          <td>{{ p.name }}</td>
          <td class="d-flex justify-content-around px-3">
            <ViewIcon />
            <EditIcon />
            <TrashIcon />
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>
