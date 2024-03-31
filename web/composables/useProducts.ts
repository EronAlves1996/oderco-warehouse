import type { RefSymbol } from '@vue/reactivity';
import type { LocationQueryValue } from 'vue-router';
import { z } from 'zod';

const productSchema = z.object({
  public_id: z.string().uuid(),
  name: z.string(),
  quantity: z.number().nonnegative().int(),
  picture: z.nullable(z.string()),
  price: z.number(),
  created_at: z.string().datetime(),
  updated_at: z.string().datetime(),
});

const paginatedProductSchema = z.object({
  data: z.array(productSchema),
  links: z.array(
    z.object({
      active: z.boolean(),
      label: z.string(),
      url: z.string().or(z.null()),
    }),
  ),
});

const handleError = (error: any) => {
  const errorData = errorDetailsSchema.safeParse(error);

  if (errorData.success) {
    const {
      data: { status, title },
    } = errorData;
    throw createError({ statusCode: status, statusMessage: title });
  }

  throw createError({
    statusCode: 500,
    statusMessage: 'Servidor não disponível!!',
  });
};

export const useProducts = (page: Ref<number>, search: Ref<string>) => {
  const result = useFetch('/api/products', {
    query: {
      page,
      s: search,
    },
    watch: [page, search],
    transform: (t) => paginatedProductSchema.parse(t),
  });

  if (result.error) {
    handleError(result.error.value?.data);
  }
  return result;
};

export const useProduct = async (id: string) => {
  const {
    data: { value },
    error,
  } = await useFetch('/api/products/' + id, { server: true });

  if (error?.value?.data) {
    const {
      value: { data },
    } = error;
    handleError(data);
  }

  return productSchema.parse(value);
};
