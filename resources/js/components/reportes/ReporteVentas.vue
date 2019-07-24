<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs11 sm3>
                <v-select
                    v-model="vendedor"
                    hint="vendedor"
                    :items="sellers"
                    item-text="name"
                    item-value="id"
                    label="vendedores"
                    box
                    @change="getReports()"
                    single-line
                    multiple
                ></v-select>
            </v-flex>
            <v-flex xs11 sm3>
                <v-select
                    v-model="condicionventa"
                    hint="condicion"
                    :items="terms"
                    label="Condicion"
                    @change="getReports()"
                    box
                    single-line
                    multiple
                ></v-select>
            </v-flex>
            <v-flex xs11 sm3>
                <v-select
                    v-model="cliente"
                    hint="cliente"
                    :items="clientes"
                    item-text="razonsocial"
                    item-value="id"
                    label="clientes"
                    box
                    @change="getReports()"
                    single-line
                    multiple
                ></v-select>
            </v-flex>
        </v-layout>

        <v-layout justify-space-around>
            <v-flex xs11>
                <v-range-selector :start-date.sync="range.start" :end-date.sync="range.end" />
            </v-flex>
        </v-layout>
        <br />
        <v-data-table hide-actions :headers="headers" :items="facturas">
            <template v-slot:items="factura">
                <td class="hidden-xs-only">
                    <v-avatar class="type-item">
                        <p class="title type">{{ factura.item.letracomprobante }}</p>
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
                <td class="hidden-xs-only">{{ factura.item.cliente.razonsocial }}</td>
                <td class="hidden-xs-only">{{ factura.item.vendedor.name }}</td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
import VRangeSelector from "vuelendar/components/vl-range-selector";
export default {
    name: "ReporteVentas",

    components: {
        VRangeSelector
    },

    data() {
        return {
            condicionventa: [],
            facturas: [],
            vendedor: [],
            sellers: [],
            clientes: [],
            cliente: [],
            range: {},
            reports: [],
            headers: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Factura", sortable: false },
                { text: "Importe", sortable: false },
                { text: "Condición", sortable: false, class: "hidden-xs-only" },
                { text: "Cliente", sortable: false, class: "hidden-xs-only" },
                { text: "Vendedor", sortable: false, class: "hidden-xs-only" }
            ],
            terms: ["CONTADO", "CREDITO / DEBITO", "CUENTA CORRIENTE"]
        };
    },

    mounted() {
        this.getSellers();
        this.getClients();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getSellers: async function() {
            let response = await this.index({ url: "api/users" });
            this.sellers = response;
        },
        getClients: async function() {
            let response = await this.index({ url: "api/clientes" });
            this.clientes = response.clientes;
        },

        getReports() {
            let data = {
                vendedor: this.vendedor,
                producto: this.producto,
                fechas: [this.range.start, this.range.end],
                condicion: this.condicionventa,
                clientes: this.cliente
            };
            axios.post("api/estadisticas/reportes", data).then(response => {
                this.facturas = response.data;
            });
        }
    }
};
</script>

<style src="vuelendar/scss/vuelendar.scss" lang="scss"></style>
