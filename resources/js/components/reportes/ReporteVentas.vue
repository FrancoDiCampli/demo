<template>
    <div v-if="ventas != null">
        <!-- Grafico Ventas Fecha -->
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
                            <ve-line :legend-visible="false" :data="ventas.ventasFechaChart"></ve-line>
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
        <!-- Grafico Ventas Vendedores -->
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
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm6 lg8 px-3>
                            <ve-pie :legend-visible="false" :data="ventasVendedoresChart"></ve-pie>
                        </v-flex>
                        <v-flex xs12 sm6 lg4 px-3>
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
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm6 px-3>
                            <!-- Input Clientes -->
                            <v-text-field
                                @keyup="findCliente()"
                                v-model="cliente"
                                :hint="clienteSearchTable ? '' : 'Escriba para buscar un cliente'"
                                persistent-hint
                                clearable
                                clear-icon="fas fa-times"
                                label="Cliente"
                                box
                            ></v-text-field>
                            <!-- Tabla Buscar Clientes -->
                            <transition name="expand">
                                <div v-show="clienteSearchTable">
                                    <div v-if="inProcess" class="search-table">
                                        <v-layout justify-center>
                                            <v-flex xs12 pa-3>
                                                <v-layout justify-center>
                                                    <v-progress-circular
                                                        indeterminate
                                                        color="primary"
                                                    ></v-progress-circular>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                    <div v-else>
                                        <v-data-table
                                            no-data-text="El cliente no se encuentra en la base de datos."
                                            hide-actions
                                            hide-headers
                                            :items="clientes"
                                            class="search-table"
                                        >
                                            <template v-slot:items="cliente">
                                                <tr
                                                    @click="fillClientes(cliente.item)"
                                                    style="cursor: pointer;"
                                                >
                                                    <td>{{ cliente.item.documentounico }}</td>
                                                    <td>{{ cliente.item.razonsocial }}</td>
                                                </tr>
                                            </template>
                                        </v-data-table>
                                    </div>
                                </div>
                            </transition>
                            <br />
                            <v-data-table :items="clientesCompareTable" hide-actions hide-headers>
                                <template v-slot:items="cliente">
                                    <td>{{ cliente.item }}</td>
                                    <td>
                                        <v-btn
                                            @click="removeCliente(cliente.item)"
                                            :disabled="cliente.item == 'CONSUMIDOR FINAL'"
                                            flat
                                            icon
                                            color="primary"
                                        >
                                            <v-icon size="medium">fas fa-times</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-flex>
                        <v-flex xs12 sm6 px-3>
                            <ve-pie :legend-visible="false" :data="ventasClientesChart"></ve-pie>
                        </v-flex>
                    </v-layout>
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
                    <v-layout justify-center>
                        <v-flex xs12>
                            <ve-pie :legend-visible="false" :data="ventasCondicionesChart"></ve-pie>
                        </v-flex>
                    </v-layout>
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
            // Data General
            ventas: null,
            ventasLimit: 5,

            // Data Ventas Fecha
            showVentasFechas: true,
            modalVentasDesde: false,
            modalVentasHasta: false,
            fechaVentasDesde: null,
            fechaVentasHasta: null,
            headersFacturasFecha: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Factura", sortable: false },
                { text: "Importe", sortable: false }
            ],

            // Data Ventas Vendedores
            showVentasVendedores: true,
            selectedVendedores: [],
            headersVendedores: [{ text: "Vendedor", sortable: false }],
            ventasVendedoresChart: {
                columns: ["vendedor", "totalVendido"],
                rows: []
            },

            // Data Ventas Clientes
            showVentasClientes: true,
            cliente: null,
            clientes: [],
            clientesCompareTable: ["CONSUMIDOR FINAL"],
            clienteSearchTable: false,
            ventasClientesChart: {
                columns: ["cliente", "totalComprado"],
                rows: []
            },

            // Data Ventas Condiciones
            showVentasCondiciones: true,
            ventasCondicionesChart: {
                columns: ["condicion", "totalVendido"],
                rows: []
            }
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

        // Metodos Generales
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
            this.compareClientes();
            this.setCondiciones();
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

        // Metodos Vendedores
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
        },

        // Metodos Clientes
        findCliente: async function() {
            if (this.cliente) {
                // Buscar Cliente
                this.clienteSearchTable = true;
                let response = await this.index({
                    url: "/api/clientes",
                    buscarCliente: this.cliente,
                    limit: 5
                });
                this.clientes = response.clientes;
            }
        },

        fillClientes(client) {
            let cliente = client.razonsocial;
            this.clientesCompareTable.push(cliente);
            this.cliente = null;
            this.clientes = [];
            this.clienteSearchTable = false;
            this.compareClientes();
        },

        removeCliente(cliente) {
            let index = this.clientesCompareTable.indexOf(cliente);
            this.clientesCompareTable.splice(index, 1);
            this.compareClientes();
        },

        compareClientes() {
            this.ventasClientesChart.rows = [];
            for (let i = 0; i < this.clientesCompareTable.length; i++) {
                let find = this.ventas.ventasClientes.rows.find(
                    cliente => cliente.cliente === this.clientesCompareTable[i]
                );

                if (find) {
                    this.ventasClientesChart.rows.push(find);
                }
            }
        },

        // Metodos Condiciones
        setCondiciones() {
            this.ventasCondicionesChart.rows = [];

            for (let i = 0; i < this.ventas.condiciones.length; i++) {
                let find = this.ventas.ventasCondiciones.rows.find(
                    condicion =>
                        condicion.condicion === this.ventas.condiciones[i]
                );
                this.ventasCondicionesChart.rows.push(find);
            }
        }
    }
};
</script>

<style>
</style>

