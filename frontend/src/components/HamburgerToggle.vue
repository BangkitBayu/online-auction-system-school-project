<script setup lang="js"></script>
<template>
  <button>
    <span></span>
    <span></span>
    <span></span>
  </button>
</template>
<style lang="css" scoped>
button {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: transparent;
  border: none;
  gap: 4px;
}

span {
  background: black;
  width: 25px;
  height: 2px;
  border-radius: 8px;
}
</style>
