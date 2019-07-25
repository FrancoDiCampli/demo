<template>
    <v-data-table hide-actions :headers="headers" :items="showData.facturas">
        <template v-slot:items="factura">
            <td class="hidden-xs-only">
                <v-avatar class="type-item">
                    <p class="title type">{{ factura.item.letracomprobante }}</p>
                </v-avatar>
            </td>
            <td>
                <div v-if="factura.item.comprobanteafip != null">{{ factura.item.comprobanteafip }}</div>
                <div v-else>{{ factura.item.id }}</div>
            </td>
            <td class="hidden-xs-only">{{factura.item.fecha}}</td>
            <td>{{factura.item.total}}</td>
            <td></td>
        </template>
    </v-data-table>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "ClientesShowFacturasTable",

    data() {
        return {
            headers: [
                { text: "Tipo", sortable: false },
                { text: "Nº Factura", sortable: false },
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
