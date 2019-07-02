<template>
    <div>
        <!-- Boton para agregar un nuevo Inventario -->
        <!-- <v-btn dark fab fixed right bottom color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>-->

        <!-- Index Inventario -->

        <InventarioIndex></InventarioIndex>
    </div>
</template>

<script>
// Components
import InventarioIndex from "../components/inventarios/InventarioIndex.vue";

export default {
    name: "Inventario",

    components: {
        InventarioIndex
    }
};
</script>

<style>
</style>

