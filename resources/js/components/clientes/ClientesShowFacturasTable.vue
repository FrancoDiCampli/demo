<template>
    <v-data-table hide-actions :headers="headers" :items="showData.facturas">
        <template v-slot:items="factura">
            <td>
                <v-avatar class="type-item">
                    <div v-if="factura.item.cae == null">
                        <p class="title type">X</p>
                    </div>
                    <div v-else>
                        <p class="title type">C</p>
                    </div>
                </v-avatar>
            </td>
            <td>{{factura.item.numfactura}}</td>
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
.type-item {
    margin: 5px 0px 5px -12px;
    border: solid 1.5px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.type {
    margin-top: 15px;
    color: #26a69a;
}
</style>
