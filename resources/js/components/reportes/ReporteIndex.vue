<template>
    <div>
        <v-tabs right hide-slider color="transparent" active-class="primary--text">
            <v-tab>Ventas</v-tab>
            <v-tab>Compras</v-tab>
            <v-tab>Productos</v-tab>
            <v-tab-item>
                <ReporteVentas></ReporteVentas>
            </v-tab-item>
            <v-tab-item>
                <ReporteCompras></ReporteCompras>
            </v-tab-item>
            <v-tab-item>
                <ReporteInventario></ReporteInventario>
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

    components: {
        ReporteVentas,
        ReporteCompras,
        ReporteInventario
    }
};
</script>
