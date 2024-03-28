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

export const useProducts = async () => {
  const {
    data: { value },
    error,
  } = await useFetch('/api/products', { server: true });

  if (error?.value?.data) {
    const {
      value: { data },
    } = error;
    handleError(data);
  }

  return z.array(productSchema).parse(value);
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
