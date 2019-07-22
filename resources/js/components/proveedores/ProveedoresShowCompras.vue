<template>
    <v-data-table hide-actions :headers="headers" :items="showData.remitos">
        <template v-slot:items="remito">
            <td>{{remito.item.numremito}}</td>
            <td class="hidden-xs-only">{{remito.item.fecha}}</td>
            <td>{{remito.item.total}}</td>
            <td></td>
        </template>
    </v-data-table>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "ProveedoresShowCompras",

    data() {
        return {
            headers: [
                { text: "Nº Remito", sortable: false },
                { text: "Fecha", sortable: false, class: "hidden-xs-only" },
                { text: "Importe", sortable: false },
                { text: "", sortable: false }
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