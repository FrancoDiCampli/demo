<template>
    <div v-if="ventas != null">
        <v-card>
            <v-card-title @click="showVentasFechas = !showVentasFechas" style="cursor: pointer;">
                <v-layout justify-center>
                    <v-flex xs12 px-2>
                        <v-layout justify-space-between>
                            <h3>Ventas por Fecha</h3>
                            <v-icon v-show="!showVentasFechas">fas fa-caret-down</v-icon>
                            <v-icon v-show="showVentasFechas">fas fa-caret-up</v-icon>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-title>
            <v-divider></v-divider>
            <v-expand-transition>
                <v-card-text v-show="showVentasFechas">
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
                    <v-layout justify-space-around>
                        <v-flex xs12 px-3>
                            <ve-line :data="ventas.ventasFechaChart"></ve-line>
                        </v-flex>
                    </v-layout>
                    <v-layout justify-space-around>
                        <v-flex xs12 px-3>
                            <v-data-table
                                hide-actions
                                :headers="headersFacturasFecha"
                                :items="ventas.ventasFecha"
                            >
                                <template v-slot:items="factura">
                                    <td class="hidden-xs-only">
                                        <v-avatar class="type-item">
                                            <p
                                                class="title type"
                                            >{{ factura.item.letracomprobante }}</p>
                                        </v-avatar>
                                    </td>
                                    <td>
                                        <div
                                            v-if="factura.item.comprobanteafip != null"
                                        >{{ factura.item.comprobanteafip }}</div>
                                        <div v-else>{{ factura.item.id }}</div>
                                    </td>
                                    <td>{{ factura.item.total }}</td>
                                    <td class="hidden-xs-only">{{ factura.item.condicionventa }}</td>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-layout>
                    <v-layout justify-center>
                        <v-btn
                            :loading="inProcess"
                            :disabled="ventas.total >= ventasLimit && !inProcess ? false : true"
                            @click="loadMoreVentas()"
                            color="primary"
                            outline
                        >Cargar Más</v-btn>
                    </v-layout>
                </v-card-text>
            </v-expand-transition>
        </v-card>
        <br />
        <v-card>
            <v-card-title
                @click="showVentasVendedores = !showVentasVendedores"
                style="cursor: pointer;"
            >
                <v-layout justify-center>
                    <v-flex xs12 px-2>
                        <v-layout justify-space-between>
                            <h3>Ventas por Vendedores</h3>
                            <v-icon v-show="!showVentasVendedores">fas fa-caret-down</v-icon>
                            <v-icon v-show="showVentasVendedores">fas fa-caret-up</v-icon>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-title>
            <v-divider></v-divider>
            <v-expand-transition>
                <v-card-text v-show="showVentasVendedores">
                    <v-layout justify-space-around>
                        <v-flex xs12 sm5 lg6 px-3>
                            <ve-pie :data="ventasVendedoresChart"></ve-pie>
                        </v-flex>
                        <v-flex xs12 sm7 lg6 px-3>
                            <v-data-table
                                v-model="selectedVendedores"
                                :headers="headersVendedores"
                                :items="ventas.vendedores"
                                select-all
                                item-key="name"
                                hide-actions
                            >
                                <template v-slot:headers="vendedor">
                                    <tr>
                                        <th>
                                            <v-checkbox
                                                :input-value="vendedor.all"
                                                :indeterminate="vendedor.indeterminate"
                                                primary
                                                hide-details
                                                @click.stop="toggleAllVendedores"
                                                color="primary"
                                            ></v-checkbox>
                                        </th>
                                        <th
                                            v-for="header in vendedor.headers"
                                            :key="header.text"
                                        >{{ header.text }}</th>
                                    </tr>
                                </template>
                                <template v-slot:items="vendedor">
                                    <tr
                                        :active="vendedor.selected"
                                        @click="vendedor.selected = !vendedor.selected; setVendedores()"
                                    >
                                        <td>
                                            <v-checkbox
                                                :input-value="vendedor.selected"
                                                primary
                                                hide-details
                                                color="primary"
                                            ></v-checkbox>
                                        </td>
                                        <td>{{ vendedor.item.name }}</td>
                                        <td>{{ vendedor.item.email }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-expand-transition>
        </v-card>
        <br />
        <v-card>
            <v-card-title
                @click="showVentasClientes = !showVentasClientes"
                style="cursor: pointer;"
            >
                <v-layout justify-center>
                    <v-flex xs12 px-2>
                        <v-layout justify-space-between>
                            <h3>Ventas por Clientes</h3>
                            <v-icon v-show="!showVentasClientes">fas fa-caret-down</v-icon>
                            <v-icon v-show="showVentasClientes">fas fa-caret-up</v-icon>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-title>
            <v-divider></v-divider>
            <v-expand-transition>
                <v-card-text v-show="showVentasClientes">
                    <ve-pie :data="ventas.ventasClientes"></ve-pie>
                </v-card-text>
            </v-expand-transition>
        </v-card>
        <br />
        <v-card>
            <v-card-title
                @click="showVentasCondiciones = !showVentasCondiciones"
                style="cursor: pointer;"
            >
                <v-layout justify-center>
                    <v-flex xs12 px-2>
                        <v-layout justify-space-between>
                            <h3>Ventas por Condiciones</h3>
                            <v-icon v-show="!showVentasCondiciones">fas fa-caret-down</v-icon>
                            <v-icon v-show="showVentasCondiciones">fas fa-caret-up</v-icon>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-title>
            <v-divider></v-divider>
            <v-expand-transition>
                <v-card-text v-show="showVentasCondiciones">
                    <ve-pie :data="ventas.ventasCondiciones"></ve-pie>
                </v-card-text>
            </v-expand-transition>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapActions, mapState } from "vuex";

export default {
    name: "ReporteVentas",

    data() {
        return {
            ventas: null,
            modalVentasDesde: false,
            modalVentasHasta: false,
            fechaVentasDesde: null,
            fechaVentasHasta: null,
            ventasLimit: 5,
            selectedVendedores: [],
            headersFacturasFecha: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Factura", sortable: false },
                { text: "Importe", sortable: false },
                { text: "Condición", sortable: false, class: "hidden-xs-only" }
            ],
            headersVendedores: [
                { text: "Nombre", sortable: false },
                { text: "Email", sortable: false }
            ],
            ventasVendedoresChart: {
                columns: ["vendedor", "totalVendido"],
                rows: []
            },
            showVentasFechas: true,
            showVentasVendedores: true,
            showVentasCondiciones: true,
            showVentasClientes: true
        };
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
            let response = await this.index({
                url: "/api/estadisticas/ventas",
                limit: this.ventasLimit,
                desde: this.fechaVentasDesde,
                hasta: this.fechaVentasHasta
            });

            this.ventas = response.ventas;

            if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
                this.fechaVentasDesde = this.ventas.fechas.desde;
            }
            if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
                this.fechaVentasHasta = this.ventas.fechas.hasta;
            }

            console.log(this.ventas);

            this.toggleAllVendedores();
        },

        loadMoreVentas: async function() {
            this.ventasLimit += 5;
            let response = await this.index({
                url: "/api/estadisticas/ventas",
                limit: this.ventasLimit,
                desde: this.fechaVentasDesde,
                hasta: this.fechaVentasHasta
            });

            this.ventas = response.ventas;

            if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
                this.fechaVentasDesde = this.ventas.fechas.desde;
            }
            if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
                this.fechaVentasHasta = this.ventas.fechas.hasta;
            }
        },

        toggleAllVendedores() {
            if (this.selectedVendedores.length) {
                this.selectedVendedores = [];
            } else {
                this.selectedVendedores = this.ventas.vendedores.slice();
            }

            this.setVendedores();
        },

        setVendedores() {
            this.ventasVendedoresChart.rows = [];
            for (let i = 0; i < this.selectedVendedores.length; i++) {
                let find = this.ventas.ventasVendedores.rows.find(
                    vendedor =>
                        vendedor.vendedor === this.selectedVendedores[i].name
                );
                this.ventasVendedoresChart.rows.push(find);
            }
        }
    }
};
</script>

<style>
</style>

