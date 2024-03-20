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

export const useProducts = async () => {
  const {
    data: { value },
  } = await useFetch('/api/products', { server: true });
  return z.array(productSchema).parse(value);
};

export const useProduct = async (id: string) => {
  const {
    data: { value },
  } = await useFetch('/api/products/' + id, { server: true });
  return productSchema.parse(value);
};
