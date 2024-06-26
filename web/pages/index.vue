<script setup lang="ts">
  useHead({
    title: 'Oderço - Produtos',
  });
  const route = useRoute();
  const { push } = useRouter();
  const pageNumber = ref(parseInt(String(route.query.page ?? 1)));
  const searchString = ref('');
  const deleteInfo = reactive({
    name: '',
    id: '',
  });
  const modal = ref<any>(null);

  watch(
    () => route.query.s,
    () => (searchString.value = String(route.query.s ?? '')),
    { immediate: true },
  );
  const { data, refresh } = await useProducts(pageNumber, searchString);

  watch(pageNumber, () => {
    push({
      path: '.',
      query: {
        ...route.query,
        page: pageNumber.value,
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

  const getModal = () => {
    if (!modal.value) {
      const { $bootstrap } = useNuxtApp();
      modal.value = new $bootstrap.Modal(
        document.getElementById('action-modal'),
      );
    }
    return modal.value;
  };

  const openDeleteModal = (id: string, name: string) => {
    deleteInfo.id = id;
    deleteInfo.name = name;
    getModal().show();
  };
  const closeModal = () => getModal().hide();
  const deleteProduct = async () => {
    await $fetch(`/api/products/${deleteInfo.id}`, {
      method: 'DELETE',
      // https://github.com/nuxt/nuxt/issues/23422
      body: {},
      onResponse: ({ response: { ok } }) => {
        if (ok) {
          getModal().hide();
          useNuxtApp().$toast.success('Produto deletado!');
          refresh();
        }
      },
      onResponseError: ({ response: { _data } }) => handleError(_data),
    });
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
          <td class="align-middle">
            {{ p.public_id }}
          </td>
          <td class="w-50 align-middle">{{ p.name }}</td>
          <td class="align-middle">
            <div class="d-flex justify-content-around px-3">
              <NuxtLink :to="`/product/${p.public_id}`">
                <button
                  type="button"
                  class="btn btn-link">
                  <ViewIcon />
                </button>
              </NuxtLink>
              <NuxtLink :to="`/product/${p.public_id}/edit`">
                <button
                  type="button"
                  class="btn btn-link">
                  <EditIcon />
                </button>
              </NuxtLink>
              <button
                type="button"
                @click="openDeleteModal(p.public_id, p.name)"
                class="btn btn-link">
                <TrashIcon />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <footer class="d-flex justify-content-center gap-2">
      <template
        v-for="link in data?.links"
        :key="link.label">
        <DefaultButton
          v-if="link.url"
          :outline="!link.active"
          @click="navigateToPage">
          {{ link.label }}
        </DefaultButton>
      </template>
    </footer>
    <ActionModal>
      <div class="p-5 d-flex flex-column gap-4">
        <h5>
          Deseja realmente deletar o produto
          <strong>{{ deleteInfo.name }}</strong
          >?
        </h5>
        <div class="d-flex gap-2">
          <DefaultButton
            outline
            class-names="flex-grow-1"
            type="button"
            @click="closeModal">
            Voltar
          </DefaultButton>
          <button
            type="button"
            class="btn btn-danger flex-grow-1 fw-bold"
            @click="deleteProduct">
            Excluir
          </button>
        </div>
      </div>
    </ActionModal>
  </section>
</template>
