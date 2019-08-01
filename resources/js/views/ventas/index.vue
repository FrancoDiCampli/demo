<template>
    <div>
        <!-- Boton para agreagar facturas -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="$router.push('/ventas/nueva')"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nueva Venta</span>
        </v-tooltip>
        <!-- Index de factura -->
        <v-card>
            <v-card-text>
                <FacturasIndex></FacturasIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Components
import FacturasIndex from "../../components/facturas/FacturasIndex.vue";

export default {
    name: "Facturas",

    data() {
        return {
            fab: false,
            tabVentas: null
        };
    },

    components: {
        FacturasIndex
    }
};
</script>

<style>
</style>