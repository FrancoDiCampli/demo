<template>
    <div>
        <v-flex>
            <ve-pie :data="ventasProductoChart"></ve-pie>
        </v-flex>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    name: "ReporteCompras",

    data() {
        return {
            ventasProductoChart: null
        };
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    mounted() {
        this.getProductos();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getProductos: async function() {
            let response = await this.index({
                url: "/api/estadisticas/detalles"
                // limit: this.ventasLimit,
                // desde: this.fechaVentasDesde,
                // hasta: this.fechaVentasHasta
            });

            this.ventasProductoChart = response.detalles.ventasProductosChart;

            // if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
            //     this.fechaVentasDesde = this.ventas.fechas.desde;
            // }
            // if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
            //     this.fechaVentasHasta = this.ventas.fechas.hasta;
            // }

            console.log(this.ventasProductoChart);
        }
    }
};
</script>

<style>
</style>
