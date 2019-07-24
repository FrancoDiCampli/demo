<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs11 sm5>
                <v-select
                    v-model="producto"
                    hint="producto"
                    :items="productos"
                    item-text="articulo"
                    item-value="id"
                    label="productos"
                    box
                    @change="getReports()"
                    single-line
                    multiple
                ></v-select>
            </v-flex>
            <v-flex xs11 sm5>
                <v-select
                    v-model="movimiento"
                    hint="movimiento"
                    :items="movimientos"
                    item-text="move"
                    item-value="move"
                    label="movimientos"
                    box
                    @change="getReports()"
                    single-line
                    multiple
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex xs11>
                <v-range-selector :start-date.sync="range.start" :end-date.sync="range.end" />
            </v-flex>
        </v-layout>
        <br />
        <v-data-table hide-actions :headers="headers" :items="reports">
            <template v-slot:items="mov">
                <td>{{ mov.item.tipo }}</td>
                <td>{{ mov.item.cantidad }}</td>
                <td>{{ mov.item.fecha }}</td>
                <td>{{ mov.item.vendedor.name }}</td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapState } from "vuex";
import VRangeSelector from "vuelendar/components/vl-range-selector";
export default {
    name: "ReporteInventarios",
    components: {
        VRangeSelector
    },
    data() {
        return {
            range: {},
            producto: null,
            productos: [],
            reports: [],
            movimiento: null,
            movimientos: [
                {
                    move: "COMPRA"
                },
                {
                    move: "VENTA"
                }
            ],
            headers: [
                { text: "Tipo", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Fecha", sortable: false },
                { text: "Vendedor", sortable: false }
            ]
        };
    },

    mounted() {
        this.getProductos();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getReports() {
            let data = {
                producto: this.producto,
                fechas: [this.range.start, this.range.end],
                movimiento: this.movimiento
            };
            axios.post("api/estadisticas/inventarios", data).then(response => {
                this.reports = response.data;
            });
        },

        getProductos: async function() {
            let response = await this.index({ url: "api/articulos" });
            this.productos = response.articulos;
        }
    }
};
</script>

<style>
</style>
