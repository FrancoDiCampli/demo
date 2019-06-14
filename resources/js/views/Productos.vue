<template>
    <div>
        <!-- Boton para agregar un nuevo producto -->
        <v-btn
            dark
            fab
            fixed
            right
            bottom
            color="primary"
            @click="createProductosDialog = !createProductosDialog"
        >
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

        <!-- Modal agregar producto -->
        <v-dialog v-model="createProductosDialog" persistent width="750">
            <v-card>
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>Nuevo Producto</h2>
                        <!-- Boton cerrar modal -->
                        <v-btn
                            @click="createProductosDialog = false"
                            flat
                            icon
                            style="margin: 0; padding: 0;"
                        >
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="productosForm" @submit.prevent="saveProductos()">
                        <ProductosForm></ProductosForm>
                        <v-layout justify-center>
                            <v-btn type="submit" color="primary">Guardar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!-- Index de productos -->
        <v-card>
            <v-card-text>
                <ProductosIndex></ProductosIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
//Vuex
import { mapActions } from "vuex";

//Components
import ProductosIndex from "../components/productos/ProductosIndex.vue";
import ProductosForm from "../components/productos/ProductosForm.vue";

export default {
    name: "Productos",

    data() {
        return {
            createProductosDialog: false
        };
    },

    components: {
        ProductosIndex,
        ProductosForm
    },

    methods: {
        ...mapActions("crudx", ["save"]),
        saveProductos: async function() {
            if (this.$refs.productosForm.validate()) {
                let response = await this.save({ url: "api/articulos" });
                console.log(response);
                this.$refs.productosForm.reset();
                this.createProductosDialog = false;
            }
        }
    }
};
</script>

<style>
</style>
