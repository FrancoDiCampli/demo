<template>
    <div>
        <!-- Boton para agregar un nuevo cliente -->
        <v-btn dark fab fixed right bottom @click="$router.push('/clientes/nuevo')" color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

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
