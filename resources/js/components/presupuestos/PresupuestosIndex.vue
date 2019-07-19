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
                                <v-list-tile @click="presupuestosPDF(presupuesto.item.id)">
                                    <v-list-tile-title>Imprimir</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile @click="facturar(presupuesto.item.id)">
                                    <v-list-tile-title>Facturar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile
                                    @click="presupuesto_id = presupuesto.item.id; eliminarPresupuestoDialog = true"
                                >
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
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

            <!-- modal anular factura -->
            <v-dialog v-model="eliminarPresupuestoDialog" width="750" persistent>
                <v-card>
                    <v-card-title>
                        <h2>¿Estás Seguro?</h2>
                    </v-card-title>
                    <v-divider></v-divider>
                    <div v-show="process">
                        <v-card-text>
                            <v-layout justify-center>
                                <v-progress-circular
                                    :size="70"
                                    :width="7"
                                    color="primary"
                                    indeterminate
                                ></v-progress-circular>
                            </v-layout>
                        </v-card-text>
                    </div>
                    <div v-show="!process">
                        <v-card-text>¿Estás seguro que deseas eliminar este Presupuesto? este cambio es irreversible</v-card-text>
                        <v-card-text>
                            <v-layout justify-end wrap>
                                <v-btn
                                    @click="eliminarPresupuestoDialog = false;"
                                    outline
                                    color="primary"
                                    :disabled="process"
                                >Cancelar</v-btn>

                                <v-btn
                                    :disabled="process"
                                    @click="eliminarPresupuesto()"
                                    color="primary"
                                >Eliminar</v-btn>
                            </v-layout>
                        </v-card-text>
                    </div>
                </v-card>
            </v-dialog>
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
            eliminarPresupuestoDialog: false,
            presupuesto_id: null,
            process: false
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess", "data", "showData"])
    },

    mounted() {
        this.index({ url: "/api/presupuestos", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index", "show", "destroy"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "/api/presupuestos", limit: this.limit });
            this.loadingButton = false;
        },

        facturar: async function(id) {
            await localStorage.setItem("presupuestoID", id);
            this.$router.push("/ventas/edit");
        },

        eliminarPresupuesto: async function() {
            this.process = true;
            await this.destroy({
                url: "/api/presupuestos/" + this.presupuesto_id
            });
            this.eliminarPresupuestoDialog = false;
            await this.index({ url: "/api/presupuestos", limit: this.limit });
            this.process = false;
        },

        presupuestosPDF: function(id) {
            window.open("/api/presupuestosPDF/" + id);
        }
    }
};
</script>

<style>
</style>