import { useRuntimeConfig } from '#imports';

const config = useRuntimeConfig();
const baseURL = config.serverUrl;

export default defineEventHandler(async (event) => {
  const query = getQuery(event);
  const concated = Object.entries(query)
    .map(([k, v]) => k + '=' + v)
    .join('&');

  return proxyRequest(
    event,
    baseURL + getRequestURL(event).pathname + '?' + concated,
    {
      headers: { Accept: 'application/json' },
    },
  );
});
