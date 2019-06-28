<template>
    <div>
        <!-- Boton para agregar un nuevo Producto -->
        <v-btn dark fab fixed right bottom color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

        <!-- Index Productos -->

        <ProductosIndex></ProductosIndex>
    </div>
</template>

<script>
// Components
import ProductosIndex from "../components/productos/ProductosIndex.vue";

export default {
    name: "Producto",

    components: {
        ProductosIndex
    }
};
</script>

<style>
</style>

