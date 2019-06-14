<template>
    <div>
        <template>
            <v-data-table hide-actions :headers="headersProductos" :items="data">
                <template v-slot:items="producto">
                    <td>{{ producto.item.codarticulo }}</td>
                    <td>{{ producto.item.articulo }}</td>
                    <td>{{ producto.item.precio }}</td>
                    <td>
                        <v-btn flat icon color="primary">
                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </template>
    </div>
</template>


<script>
//Vuex
import { mapActions, mapState } from "vuex";

export default {
    name: "ProductosIndex",

    data() {
        return {
            headersProductos: [
                { text: "Codigo", sortable: false },
                { text: "Producto", sortable: false },
                { text: "Precio", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },

    computed: {
        ...mapState("crudx", ["data"])
    },

    mounted() {
        this.index({ url: "api/articulos" });
    },

    methods: {
        ...mapActions("crudx", ["index"])
    }
};
</script>

<style>
</style>
