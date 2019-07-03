<template>
    <div>
        <template>
            <v-data-table
                hide-actions
                :headers="headers"
                :items="data.remitos"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="factura">
                    <td class="hidden-xs-only">
                        <v-avatar class="type-item">
                            <div v-if="factura.item.cae == null">
                                <p class="title type">X</p>
                            </div>
                            <div v-else>
                                <p class="title type">C</p>
                            </div>
                        </v-avatar>
                    </td>
                    <td>
                        <div
                            v-if="factura.item.comprobanteafip != null"
                        >{{ factura.item.comprobanteafip }}</div>
                        <div v-else>{{ factura.item.id }}</div>
                    </td>
                    <td class="hidden-sm-and-down">{{ factura.item.cuit }}</td>
                    <td>{{ factura.item.total }}</td>
                    <td class="hidden-xs-only">{{ factura.item.fecha }}</td>
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
                                <v-list-tile
                                    v-show="factura.item.cae == null"
                                    @click="factura_id = factura.item.id; grabarFacturasDialog = true;"
                                >
                                    <v-list-tile-title>Grabar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile v-show="factura.item.cae == null">
                                    <v-list-tile-title>Anular</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </template>
            </v-data-table>
            <v-layout justify-center>
                <v-btn
                    :loading="loadingButton"
                    :disabled="limit >= data.total || loadingButton"
                    @click="loadMore()"
                    color="primary"
                    outline
                >Cargar Más</v-btn>
            </v-layout>
        </template>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "ClientesIndex",

    data() {
        return {
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Remito", sortable: false },
                { text: "CUIT", sortable: false, class: "hidden-sm-and-down" },
                { text: "Importe", sortable: false },
                { text: "Fecha", sortable: false, class: "hidden-xs-only" },
                { text: "", sortable: false }
            ]
        };
    },
    computed: {
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/remitos", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index"]),
        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "api/remitos", limit: this.limit });
            this.loadingButton = false;
        }
    }
};
</script>

<style>
</style>
