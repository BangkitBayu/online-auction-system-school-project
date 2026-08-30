<script setup lang="js"></script>

<template>
  <div class="wrapper">
    <aside>
      <slot name="aside"></slot>
    </aside>

    <header>
      <slot name="header"></slot>
    </header>
    <main>
      <slot name="main"></slot>
    </main>
  </div>
</template>
<style lang="css" scoped>
.wrapper {
  display: grid;
  min-height: 100vh;
  grid-template-areas:
    'aside header'
    'aside main';

  grid-template-rows: auto 1fr;
  grid-template-columns: auto 1fr;
  position: relative;
}

aside {
  grid-area: aside !important;
}
header {
  grid-area: header !important;
  border-bottom: 1px solid #e5e5e5;
  background: white;
}

main {
  grid-area: main !important;
  padding: 1.5rem;
  width: auto;
  background-color: #f0f0f0;
  overflow: auto;
}

@media (max-width: 768px) {
  main {
    padding: 1.5rem;
  }
}
</style>
