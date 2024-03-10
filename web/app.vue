<template>
  <header
    class="d-flex align-items-center gap-5 mx-auto border-bottom border-3"
  >
    <MainLogo />
    <input
      type="text"
      class="form-control w-75"
      placeholder="Pesquisar..."
      aria-label="Pesquisar..."
    />
  </header>
  <slot />
</template>
