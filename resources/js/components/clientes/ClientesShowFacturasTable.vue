<template>
    <v-data-table hide-actions :headers="headers" :items="showData.facturas">
        <template v-slot:items="factura">
            <td>
                <div v-if="factura.item.cae == null">
                    <v-avatar style="margin-top: 5px;" color="teal lighten-5">
                        <p class="title" style="margin-top: 12px;">X</p>
                    </v-avatar>
                </div>
                <div v-else>
                    <v-avatar style="margin-top: 5px;" color="teal lighten-5">
                        <p class="title" style="margin-top: 12px;">C</p>
                    </v-avatar>
                </div>
            </td>
            <td>{{factura.item.numfactura}}</td>
            <td>{{factura.item.fecha}}</td>
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
                { text: "Fecha", sortable: false },
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
