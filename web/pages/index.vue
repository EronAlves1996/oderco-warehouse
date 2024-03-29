<script setup lang="ts">
  const route = useRoute();
  const { push } = useRouter();
  const pageNumber = ref(parseInt(String(route.query.page ?? 1)));
  const searchString = ref('');
  watch(
    () => route.query.s,
    () => (searchString.value = String(route.query.s ?? '')),
    { immediate: true },
  );
  const { data } = await useProducts(pageNumber, searchString);

  watch(pageNumber, () => {
    push({
      path: '.',
      query: {
        page: pageNumber.value,
        searchString: searchString?.value ?? undefined,
      },
    });
  });

  const navigateToPage = (e: MouseEvent) => {
    const button = e?.target as HTMLButtonElement;
    const possibleLink = data?.value?.links
      .filter(({ label }) => button.textContent?.includes(label))
      .filter(({ url }) => url)
      .map(({ url }) => url?.split('=')[1]);

    if (possibleLink?.length != 0) {
      pageNumber.value = Number(possibleLink?.[0] ?? 1);
    }
  };
</script>
<template>
  <PageHeader
    title="Lista de Produtos Cadastrados"
    :render-add-product-button="true" />
  <section class="mt-5">
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="text-secondary">Imagem</th>
          <th class="text-secondary w-25">Código</th>
          <th
            colspan="2"
            class="text-secondary">
            Nome
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="p in data?.data"
          :key="p.public_id">
          <td>
            <div
              style="width: 112px; height: 112px"
              class="d-flex align-items-center justify-content-center">
              <img
                :src="p.picture ?? undefined"
                class="w-100" />
            </div>
          </td>
          <td>{{ p.public_id }}</td>
          <td class="w-50">{{ p.name }}</td>
          <td class="d-flex justify-content-around px-3">
            <NuxtLink :to="`/product/${p.public_id}`">
              <ViewIcon />
            </NuxtLink>
            <NuxtLink :to="`/product/${p.public_id}/edit`">
              <EditIcon />
            </NuxtLink>
            <TrashIcon />
          </td>
        </tr>
      </tbody>
    </table>
    <footer class="d-flex justify-content-center gap-2">
      <DefaultButton
        v-for="link in data?.links"
        :outline="!link.active"
        @click="navigateToPage"
        >{{ link.label }}
      </DefaultButton>
    </footer>
  </section>
</template>
