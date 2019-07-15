<template>
    <div>
        <!-- Boton para agregar un nuevo remito -->
        <v-btn dark fab fixed right bottom @click="$router.push('/compras/nueva')" color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
        <!-- Index Remitos -->
        <v-card>
            <v-card-text>
                <RemitosIndex></RemitosIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import RemitosIndex from "../../components/proveedores/RemitosIndex.vue";
export default {
    name: "Remitos",

    components: {
        RemitosIndex
    }
};
</script>

<style>
</style>
