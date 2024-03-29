<template>
  <div
    class="modal fade"
    id="action-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalLabel"
    aria-hidden="true">
    <div
      class="modal-dialog modal-dialog-centered"
      role="document">
      <div class="modal-content">
        <slot />
      </div>
    </div>
  </div>
</template>
