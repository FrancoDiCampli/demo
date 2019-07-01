<template>
    <v-card>
        <v-card-text>
            <v-layout justify-space-between>
                <h2>Nuevo Producto</h2>
            </v-layout>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
            <!-- Formulario formulario para agregar un cliente -->
            <v-form ref="productosForm" @submit.prevent="saveProducto">
                <ProductosForm></ProductosForm>
                <v-layout justify-center>
                    <v-btn :disabled="inProcess" type="submit" color="primary">Guardar</v-btn>
                </v-layout>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
// Components
import ProductosForm from "../components/productos/ProductosForm.vue";

// Vuex
import { mapActions, mapState } from "vuex";

export default {
    name: "ProductoNew",

    components: {
        ProductosForm
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    methods: {
        ...mapActions("crudx", ["save"]),
        saveProducto() {}
    }
};
</script>

<style>
</style>
