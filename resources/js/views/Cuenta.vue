<template>
    <div>
        <!-- Boton para agregar una nueva factura (En proceso) -->
        <v-btn dark fab fixed right bottom color="primary" @click="$router.push('/cuenta/nueva')">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
        <!-- Index de factura -->
        <v-card>
            <v-card-text>
                <CuentaIndex></CuentaIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

// Components
import CuentaIndex from "../components/cuenta/CuentaIndex.vue";

export default {
    name: "Cuenta",

    data() {
        return {};
    },
    components: {
        CuentaIndex
    }
};
</script>

<style>
</style>
