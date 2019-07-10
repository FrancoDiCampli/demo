<template>
    <v-data-table hide-actions :headers="headers" :items="showData.inventarios">
        <template v-slot:items="inventario">
            <td>{{ inventario.item.cantidad }}</td>
            <td>{{ inventario.item.lote }}</td>
            <td>{{ inventario.item.vencimiento }}</td>
            <td>{{ inventario.item.proveedor_id }}</td>
        </template>
    </v-data-table>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "ProductosShowInventario",

    data() {
        return {
            headers: [
                { text: "Cantidad", sortable: false },
                { text: "Lote", sortable: false },
                { text: "Vencimiento", sortable: false },
                { text: "Proveedor", sortable: false }
            ]
        };
    },

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>

<style>
</style>
