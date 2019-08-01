<template>
    <div>
        <!-- Boton para agregar una nueva compra -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="$router.push('/compras/nueva')"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Remito de Compra</span>
        </v-tooltip>

        <!-- Index Comptar -->
        <v-card>
            <v-card-text>
                <RemitosIndex></RemitosIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Components
import RemitosIndex from "../../components/remitos/RemitosIndex.vue";

export default {
    name: "Remitos",

    components: {
        RemitosIndex
    }
};
</script>

<style>
</style>
