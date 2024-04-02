import {
  required,
  numeric,
  minValue,
  integer,
  maxLength,
  decimal,
  helpers,
} from '@vuelidate/validators';

const { withMessage } = helpers;

export const requiredWithMessage = withMessage('Campo é obrigatório', required);
const numericWithMessage = withMessage('Permitido apenas números!', numeric);
const nonNegative = withMessage('Campo não pode ser negativo!', minValue(0));
const imageValidator = (f: File) =>
  f === null ||
  f === undefined ||
  ['jpg', 'png', 'jpeg'].some((format) => f.type.endsWith(format));

export const getValidationRules = () => ({
  name: {
    requiredWithMessage,
    maxLength: withMessage('Campo suporta até 100 caracteres', maxLength(100)),
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
});
