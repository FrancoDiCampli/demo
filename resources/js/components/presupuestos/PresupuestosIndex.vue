<template>
    <div>
        <!-- Tabla de Facturas -->
        <template>
            <v-data-table
                hide-actions
                no-data-text="No existe ningun presupuesto registrado"
                :headers="headers"
                :items="data.presupuestos"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="presupuesto">
                    <td>{{ presupuesto.item.numpresupuesto }}</td>
                    <td class="hidden-sm-and-down">{{ presupuesto.item.cuit }}</td>
                    <td>{{ presupuesto.item.total }}</td>
                    <td class="hidden-xs-only">{{ presupuesto.item.fecha }}</td>
                    <!-- menu acciones de presupuestos -->
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title>Facturar</v-list-tile-title>
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
    name: "PresupuestosIndex",

    data() {
        return {
            presupuestos: [],
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Nº Presupuesto", sortable: false },
                { text: "CUIT", sortable: false, class: "hidden-sm-and-down" },
                { text: "Importe", sortable: false },
                { text: "Fecha", sortable: false, class: "hidden-xs-only" },
                { text: "", sortable: false }
            ],
            grabarFacturasDialog: false,
            factura_id: null,
            process: false
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess", "data"])
    },

    mounted() {
        this.index({ url: "/api/presupuestos", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "/api/presupuestos", limit: this.limit });
            this.loadingButton = false;
        }
    }
};
</script>

<style>
</style>