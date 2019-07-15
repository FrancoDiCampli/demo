<template>
    <div>
        <!-- Boton para agregar una nueva compra -->
        <v-btn dark fab fixed right bottom @click="$router.push('/compras/nueva')" color="primary">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

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
