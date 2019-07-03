<template>
    <div>
        <!-- Boton para agregar una nueva factura (En proceso) -->
        <v-btn dark fab fixed right bottom color="primary" @click="$router.push('/compras/nueva')">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
        <!-- Index de factura -->
        <v-card>
            <v-card-text>
                <RemitosIndex></RemitosIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import RemitosIndex from "../components/remitos/RemitosIndex.vue";

export default {
    name: "Remitos",

    components: {
        RemitosIndex
    }
};
</script>

<style>
</style>
