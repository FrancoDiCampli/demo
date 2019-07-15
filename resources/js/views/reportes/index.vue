<template>
    <div>
        <v-tabs fixed-tabs grow slider-color="primary" active-class="primary--text">
            <v-tab>Ventas</v-tab>
            <v-tab>Compras</v-tab>
            <v-tab>Productos</v-tab>
            <v-tab-item>
                <br />
                <ReporteVentas></ReporteVentas>
            </v-tab-item>
            <v-tab-item>
                <br />
                <ReporteCompras></ReporteCompras>
            </v-tab-item>
            <v-tab-item>
                <br />
                <ReporteInventario></ReporteInventario>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
// components
import ReporteVentas from "../../components/reportes/ReporteVentas.vue";
import ReporteCompras from "../../components/reportes/ReporteCompras.vue";
import ReporteInventario from "../../components/reportes/ReporteInventarios.vue";

export default {
    name: "Reportes",

    components: {
        ReporteVentas,
        ReporteCompras,
        ReporteInventario
    }
};
</script>
