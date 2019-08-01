<template>
    <div>
        <!-- Boton para agreagar presupuesto -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="$router.push('/presupuestos/nuevo')"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Presupuesto</span>
        </v-tooltip>

        <!-- Index de presupuestos -->
        <v-card>
            <v-card-text>
                <PresupuestosIndex></PresupuestosIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Components
import PresupuestosIndex from "../../components/presupuestos/PresupuestosIndex.vue";

export default {
    name: "Presupuestos",

    components: {
        PresupuestosIndex
    }
};
</script>

<style>
</style>
