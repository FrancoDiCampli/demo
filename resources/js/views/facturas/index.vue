<template>
    <div>
        <!-- Boton para agreagar facturas y presupuesto -->
        <v-speed-dial v-model="fab" bottom fixed right transition="scale-transition">
            <template v-slot:activator>
                <v-btn v-model="fab" color="primary" dark fab>
                    <v-icon style="display: inline-flex;">fas fa-plus</v-icon>
                    <v-icon style="display: inline-flex;">fas fa-times</v-icon>
                </v-btn>
            </template>
            <!-- agregar presupuesto -->
            <v-tooltip left>
                <v-btn fab dark small color="primary" slot="activator">
                    <v-icon style="display: inline-flex;">fas fa-file</v-icon>
                </v-btn>
                <span>Presupuesto</span>
            </v-tooltip>
            <!-- agregar facturas -->
            <v-tooltip left>
                <v-btn
                    fab
                    dark
                    small
                    color="primary"
                    slot="activator"
                    @click="$router.push('facturas/nueva')"
                >
                    <v-icon style="display: inline-flex;">fas fa-dollar-sign</v-icon>
                </v-btn>
                <span>Factura</span>
            </v-tooltip>
        </v-speed-dial>

        <!-- Index de factura -->
        <v-card>
            <v-card-text>
                <FacturasIndex></FacturasIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import FacturasIndex from "../../components/facturas/FacturasIndex.vue";

export default {
    name: "Factura",

    data() {
        return {
            fab: false
        };
    },

    components: {
        FacturasIndex
    }
};
</script>

<style>
</style>
