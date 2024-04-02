import { useRuntimeConfig } from '#imports';

const config = useRuntimeConfig();
const baseURL = config.serverUrl;

export default defineEventHandler(async (event) => {
  return proxyRequest(event, baseURL + getRequestURL(event).pathname, {
    headers: { Accept: 'application/json' },
  });
});
