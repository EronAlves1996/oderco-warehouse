import { useRuntimeConfig } from '#imports';

const config = useRuntimeConfig();
const baseURL = config.serverUrl;

export default defineEventHandler(async (event) => {
  const { method } = event;
  const params = getQuery(event);

  const body = method === 'GET' ? undefined : await readBody(event);

  const headers = getHeaders(event);

  const response = await $fetch.raw(getRequestURL(event).pathname, {
    headers: {
      'Content-Type': headers['content-type'] ?? 'application/json',
      Accept: headers.accept ?? 'application/json',
    },
    baseURL,
    method,
    params,
    body,
    ignoreResponseError: true,
  });

  for (let [k, v] of response.headers.entries()) {
    setResponseHeader(event, k, v);
  }
  setResponseStatus(event, response.status, response.statusText);

  return response._data;
});
