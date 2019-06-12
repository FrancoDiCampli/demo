<template>
    <div>
        <!-- Boton para agregar una nueva factura (En proceso) -->
        <v-btn dark fab fixed right bottom color="primary" @click="$router.push('/ventas/nueva')">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
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
import FacturasIndex from "../components/facturas/FacturasIndex.vue";

export default {
    name: "Factura",

    components: {
        FacturasIndex
    }
};
</script>

<style>
</style>
