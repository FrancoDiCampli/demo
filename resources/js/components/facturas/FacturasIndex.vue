<template>
    <div>
        <!-- Facturas Table -->
        <template>
            <v-data-table hide-actions :headers="headers" :items="data">
                <template v-slot:items="factura">
                    <td>
                        <div v-if="factura.item.cae == null">
                            <v-avatar style="margin: 5px 0px 5px -12px;" color="teal lighten-5">
                                <p class="title" style="margin-top: 12px;">X</p>
                            </v-avatar>
                        </div>
                        <div v-else>
                            <v-avatar style="margin: 5px 0px 5px -12px;" color="teal lighten-5">
                                <p class="title" style="margin-top: 12px;">C</p>
                            </v-avatar>
                        </div>
                    </td>
                    <td>
                        <div
                            v-if="factura.item.comprobanteafip != null"
                        >{{ factura.item.comprobanteafip }}</div>
                        <div v-else>{{ factura.item.id }}</div>
                    </td>
                    <td class="hidden-sm-and-down">{{ factura.item.cuit }}</td>
                    <td>{{ factura.item.total }}</td>
                    <td>{{ factura.item.fecha }}</td>
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title>Imprimir</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile v-show="factura.item.cae == null">
                                    <v-list-tile-title>Grabar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile v-show="factura.item.cae == null">
                                    <v-list-tile-title>Anular</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                    <td></td>
                </template>
            </v-data-table>
        </template>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    name: "ClientesIndex",

    data() {
        return {
            headers: [
                { text: "Tipo", sortable: false },
                { text: "Nº Factura", sortable: false },
                { text: "CUIT", sortable: false, class: "hidden-sm-and-down" },
                { text: "Importe", sortable: false },
                { text: "Fecha", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },

    computed: {
        ...mapState("crudx", ["data"])
    },

    mounted() {
        this.index({ url: "api/facturas" });
    },

    methods: {
        ...mapActions("crudx", ["index"])
    }
};
</script>

<style>
</style>