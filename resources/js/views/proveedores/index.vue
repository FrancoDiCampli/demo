<template>
    <div>
        <!-- Boton para agregar un nuevo cliente -->
        <v-btn dark fab fixed right bottom @click="$router.push('/proveedores/nuevo')" color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

        <!-- Index Cliente -->
        <v-card>
            <v-card-text>
                <ProveedoresIndex></ProveedoresIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Components
import ProveedoresIndex from "../../components/proveedores/ProveedoresIndex.vue";

export default {
    name: "Proveedores",

    components: {
        ProveedoresIndex
    }
};
</script>

<style>
</style>

