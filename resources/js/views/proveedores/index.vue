<template>
    <div>
        <!-- Boton para agregar un nuevo proveedor -->
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="$router.push('/proveedores/nuevo')"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Proveedor</span>
        </v-tooltip>
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

