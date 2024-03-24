import { z } from 'zod';

export const handleError = (_data: any) => {
  const errorDetailsSchema = z.object({
    detail: z.string().or(z.record(z.array(z.string()))),
    instance: z.string(),
    status: z.number().int(),
    title: z.string(),
    type: z.string(),
  });
  const result = errorDetailsSchema.safeParse(_data);

  if (result.success) {
    const {
      data: { detail },
    } = result;

    if (typeof detail === 'object') {
      Object.values(detail)
        .flat(1)
        .forEach((e) => useToast().error(e));
      return;
    }

    useToast().error(detail);
    return;
  }

  useToast().error(
    'Não foi possível conectar com o servidor! Cheque sua conexão de internet e tente novamente!',
  );
};
