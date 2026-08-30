<script setup lang="js"></script>

<template>
  <table>
    <thead>
      <slot name="tableHead"></slot>
    </thead>
    <tbody>
      <slot name="tableBody"></slot>
    </tbody>
  </table>
</template>

<style lang="css" scoped>
table {
  border-collapse: collapse;
  border: none;
  outline: none;
  width: 100%;
}

thead {
  background-color: #e4e4e4;
  border-radius: 5px;
  width: 100%;
}

tbody {
  width: 100%;
}
</style>
