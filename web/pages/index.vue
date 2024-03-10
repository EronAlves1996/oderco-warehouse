<script setup lang="ts">
import { z } from "zod";

const {
  data: { value },
  error,
} = await useFetch("/api/products");

const productSchema = z.object({
  public_id: z.string().uuid(),
  name: z.string(),
  quantity: z.number().nonnegative().int(),
  picture_path: z.string().optional(),
  price: z.number(),
  created_at: z.string().datetime(),
  updated_at: z.string().datetime(),
});

const productsApiSchema = z.array(productSchema);
const products = productsApiSchema.parse(value);
</script>
<template>
  <div class="d-flex flex-column">
    <header class="d-flex justify-content-between align-items-center gap-3">
      <h4 class="fw-bold">Lista de Produtos Cadastrados</h4>
      <a href="#">
        <button type="button" class="btn btn-primary py-2 px-4 fw-bold">
          + Adicionar Produto
        </button>
      </a>
    </header>
    <section class="mt-5">
      <table class="table table-hover">
        <thead>
          <th class="text-secondary">Imagem</th>
          <th class="text-secondary w-25">Código</th>
          <th colspan="2" class="text-secondary">Nome</th>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.public_id">
            <td>
              <img :src="p.picture_path" />
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
  </div>
</template>
