<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs11 sm6>
                <v-select
                    v-model="proveedor"
                    hint="proveedor"
                    :items="suppliers"
                    item-text="razonsocial"
                    item-value="id"
                    label="proveedores"
                    box
                    @change="getCompras()"
                    single-line
                    multiple
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout justify-center>
            <v-flex xs11>
                <v-range-selector :start-date.sync="range.start" :end-date.sync="range.end" />
            </v-flex>
        </v-layout>
        <br />
        <v-data-table hide-actions :headers="headers" :items="remitos">
            <template v-slot:items="remito">
                <td>{{ remito.item.fecha }}</td>
                <td>{{ remito.item.total }}</td>
                <td>{{ remito.item.proveedor.razonsocial }}</td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapState } from "vuex";
import VRangeSelector from "vuelendar/components/vl-range-selector";
export default {
    name: "ReporteCompras",
    components: {
        VRangeSelector
    },
    data() {
        return {
            remitos: [],
            proveedor: [],
            suppliers: [],
            range: {},
            reports: [],
            headers: [
                { text: "Fecha", sortable: false },
                { text: "Importe", sortable: false },
                { text: "Proveedor", sortable: false }
            ]
        };
    },

    mounted() {
        this.getSuppliers();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getCompras() {
            let data = {
                fechas: [this.range.start, this.range.end],
                proveedor: this.proveedor
            };
            axios.post("api/estadisticas/compras", data).then(response => {
                this.remitos = response.data;
            });
        },

        getSuppliers: async function() {
            let response = await this.index({ url: "api/suppliers" });
            this.suppliers = response.proveedores;
        }
    }
};
</script>

<style>
</style>
