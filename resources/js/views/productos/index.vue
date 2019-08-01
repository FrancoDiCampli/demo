<template>
    <div>
        <!-- Boton para agregar un nuevo Producto -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    color="primary"
                    @click="$router.push('/productos/nuevo')"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Producto</span>
        </v-tooltip>
        <!-- Index Productos -->
        <ProductosIndex></ProductosIndex>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import ProductosIndex from "../../components/productos/ProductosIndex.vue";
import ProductosForm from "../../components/productos/ProductosForm.vue";

export default {
    name: "Producto",

    data() {
        return {
            createProductosDialog: false
        };
    },

    components: {
        ProductosIndex,
        ProductosForm
    },

    computed: {
        ...mapState("crudx", ["inProcess"])
    },

    methods: {
        ...mapActions("crudx", ["save"])
    }
};
</script>

<style>
</style>

