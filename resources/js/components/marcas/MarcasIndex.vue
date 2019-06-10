<template>
  <div>
    <!-- Clientes Table -->
    <template>
        <h2>Lista de Marcas</h2>
    </template>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  name: "MarcasIndex",

  data() {
    return {};
  },

  computed: {
    ...mapState("crudx", ["data"])
  },

  mounted() {
    this.index({ url: "api/marcas" });
  },

  methods: {
    ...mapActions("crudx", ["index", "show"])
  }
};
</script>

<style>
</style>
