<template>
    <div>
        <!-- Boton para agregar un nuevo Producto -->
        <v-btn dark fab fixed right bottom color="primary" @click="createProductosDialog = true">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
        <!-- Modal nuevo producto -->
        <v-dialog v-model="createProductosDialog" width="750" persistent>
            <v-card>
                <!-- Modal Header -->
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>Nuevo Producto</h2>
                        <!-- Boton cerrar modal -->
                        <v-btn
                            @click="createProductosDialog = false; $refs.productosForm.reset();"
                            flat
                            icon
                            style="margin: 0; padding: 0;"
                        >
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <!-- Modal body -->
                <v-card-text>
                    <template>
                        <!-- Barra de progreso circular -->
                        <div class="loading" v-show="inProcess">
                            <v-layout justify-center>
                                <v-progress-circular
                                    :size="70"
                                    :width="7"
                                    color="primary"
                                    indeterminate
                                ></v-progress-circular>
                            </v-layout>
                        </div>
                    </template>
                    <!-- Formulario para agregar un producto -->
                    <v-form ref="productosForm" @submit.prevent="saveProducto">
                        <!-- Componente Formulario -->
                        <ProductosForm></ProductosForm>
                        <v-layout justify-center>
                            <v-btn :disabled="inProcess" type="submit" color="primary">Guardar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Index Productos -->
        <ProductosIndex></ProductosIndex>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import ProductosIndex from "../components/productos/ProductosIndex.vue";
import ProductosForm from "../components/productos/ProductosForm.vue";

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
        ...mapActions("crudx", ["save"]),

        saveProducto() {}
    }
};
</script>

<style>
</style>

