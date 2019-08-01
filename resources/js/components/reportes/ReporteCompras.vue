<template>
    <div>
        <v-flex xs12 sm5 lg6 px-3>
            <ve-pie :data="comprasFechasChart"></ve-pie>
        </v-flex>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    name: "ReporteCompras",

    data() {
        return {
            compras: null,
            comprasFechasChart: null
        };
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    mounted() {
        this.getCompras();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getCompras: async function() {
            let response = await this.index({
                url: "/api/estadisticas/compras"
                // limit: this.ventasLimit,
                // desde: this.fechaVentasDesde,
                // hasta: this.fechaVentasHasta
            });

            this.comprasFechasChart = response.compras.comprasFechasChart;

            // if (this.fechaVentasDesde == null || this.fechaVentasDesde == "") {
            //     this.fechaVentasDesde = this.ventas.fechas.desde;
            // }
            // if (this.fechaVentasHasta == null || this.fechaVentasHasta == "") {
            //     this.fechaVentasHasta = this.ventas.fechas.hasta;
            // }

            console.log(this.comprasFechasChart);
        }
    }
};
</script>

<style>
</style>
