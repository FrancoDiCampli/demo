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
                <template v-slot:items="remito">
                    <td>
                        <div>{{ remito.item.id }}</div>
                    </td>
                    <td class="hidden-sm-and-down">{{ remito.item.proveedor.razonsocial }}</td>
                    <td>{{ remito.item.total }}</td>
                    <td class="hidden-xs-only">{{ remito.item.fecha }}</td>
                    <!-- menu acciones de facturas -->
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <!-- imprimir factura -->
                                <v-list-tile @click="comprasPDF(remito.item.id)">
                                    <v-list-tile-title>Imprimir</v-list-tile-title>
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
    name: "RemitosIndex",

    data() {
        return {
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Nº Remito", sortable: false },
                {
                    text: "Proveedor",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
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
        this.index({ url: "/api/remitos", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "/api/remitos", limit: this.limit });
            this.loadingButton = false;
        },

        comprasPDF: function(id) {
            window.open("/api/comprasPDF/" + id);
        }
    }
};
</script>

<style>
</style>
