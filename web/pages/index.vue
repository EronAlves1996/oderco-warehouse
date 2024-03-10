<template>
  <div class="d-flex flex-column">
    <header class="d-flex justify-content-between align-items-center gap-3">
      <h3 class="fw-bold">Lista de Produtos Cadastrados</h3>
      <a href="#">
        <button type="button" class="btn btn-primary py-2 px-4 fw-bold">
          + Adicionar Produto
        </button>
      </a>
    </header>
    <section class="mt-5">
      <table class="table">
        <thead>
          <th class="text-secondary">Imagem</th>
          <th class="text-secondary">Código</th>
          <th colspan="2" class="text-secondary">Nome</th>
        </thead>
        <tbody>
          <tr>
            <td>Imagem</td>
            <td>Código</td>
            <td>Nome</td>
            <td>Botões ação</td>
          </tr>
        </tbody>
      </table>
    </section>
  </div>
</template>
