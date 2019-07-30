<template>
    <div>
        <v-tabs right hide-slider color="transparent" active-class="primary--text">
            <v-tab>Ventas</v-tab>
            <v-tab>Compras</v-tab>
            <v-tab>Productos</v-tab>
            <v-tab-item>
                <v-card>
                    <v-card-text v-if="ventas != null">
                        <v-layout justify-space-around wrap>
                            <v-flex xs12 sm6 px-3>
                                <v-dialog
                                    ref="ventasDesde"
                                    v-model="modalVentasDesde"
                                    :return-value.sync="fechaVentasDesde"
                                    persistent
                                    lazy
                                    full-width
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="fechaVentasDesde"
                                            label="Fecha Desde"
                                            box
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="fechaVentasDesde"
                                        @change="getVentas()"
                                        scrollable
                                        locale="es"
                                        format="YYYYMMDD"
                                        color="primary"
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            flat
                                            color="primary"
                                            @click="modalVentasDesde = false"
                                        >Cancel</v-btn>
                                        <v-btn
                                            flat
                                            color="primary"
                                            @click="$refs.ventasDesde.save(fechaVentasDesde)"
                                        >OK</v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-flex>
                            <v-flex xs12 sm6 px-3>
                                <v-dialog
                                    ref="ventasHasta"
                                    v-model="modalVentasHasta"
                                    :return-value.sync="fechaVentasHasta"
                                    persistent
                                    lazy
                                    full-width
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="fechaVentasHasta"
                                            label="Fecha Hasta"
                                            box
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="fechaVentasHasta"
                                        @change="getVentas()"
                                        scrollable
                                        locale="es"
                                        format="YYYYMMDD"
                                        color="primary"
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            flat
                                            color="primary"
                                            @click="modalVentasHasta = false"
                                        >Cancel</v-btn>
                                        <v-btn
                                            flat
                                            color="primary"
                                            @click="$refs.ventasHasta.save(fechaVentasHasta)"
                                        >OK</v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-flex>
                        </v-layout>
                        <ReporteVentas :ventas="ventas.ventas"></ReporteVentas>
                        <v-layout justify-center>
                            <v-btn
                                :loading="inProcess"
                                :disabled="inProcess"
                                @click="loadMoreVentas()"
                                color="primary"
                                outline
                            >Cargar Más</v-btn>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card>
                    <v-card-text>
                        <ReporteCompras></ReporteCompras>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card>
                    <v-card-text>
                        <ReporteInventario></ReporteInventario>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
// Components
import ReporteVentas from "./ReporteVentas.vue";
import ReporteCompras from "./ReporteCompras.vue";
import ReporteInventario from "./ReporteInventarios.vue";

// Vuex
import { mapActions, mapState } from "vuex";

export default {
    name: "Reportes",

    data() {
        return {
            modalVentasDesde: false,
            modalVentasHasta: false,
            fechaVentasDesde: null,
            fechaVentasHasta: null,
            ventasLimit: 5,
            ventas: null
        };
    },

    components: {
        ReporteVentas,
        ReporteCompras,
        ReporteInventario
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    mounted() {
        this.getVentas();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getVentas: async function() {
            this.ventas = await this.index({
                url: "/api/estadisticas/ventas",
                limit: this.ventasLimit,
                desde: this.fechaVentasDesde,
                hasta: this.fechaVentasHasta
            });
            if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
                this.fechaVentasDesde = this.ventas.ventas.fechas.desde;
            }
            if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
                this.fechaVentasHasta = this.ventas.ventas.fechas.hasta;
            }

            console.log(this.ventas);
        },

        loadMoreVentas: async function() {
            this.ventasLimit += 5;
            this.ventas = await this.index({
                url: "/api/estadisticas/ventas",
                limit: this.ventasLimit,
                desde: this.fechaVentasDesde,
                hasta: this.fechaVentasHasta
            });
            if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
                this.fechaVentasDesde = this.ventas.ventas.fechas.desde;
            }
            if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
                this.fechaVentasHasta = this.ventas.ventas.fechas.hasta;
            }
        }
    }
};
</script>
