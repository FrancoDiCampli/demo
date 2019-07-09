<template>
    <div></div>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "ProductosShow",

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>

<style>
</style>
