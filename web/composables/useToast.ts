import { useNuxtApp } from '#app';

export const useToast = () => useNuxtApp().$toast;
