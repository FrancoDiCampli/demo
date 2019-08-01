<template>
    <div>
        <!-- Boton para agregar un nuevo cliente -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="$router.push('/clientes/nuevo')"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Cliente</span>
        </v-tooltip>

        <!-- Index Cliente -->
        <v-card>
            <v-card-text>
                <ClientesIndex></ClientesIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Components
import ClientesIndex from "../../components/clientes/ClientesIndex.vue";

export default {
    name: "Clientes",

    components: {
        ClientesIndex
    }
};
</script>

<style>
</style>
