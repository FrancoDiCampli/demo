<template>
    <div>
        <v-btn dark fab fixed right bottom @click="$router.push('/facturas')" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <FacturasForm></FacturasForm>
        </v-card>
    </div>
</template>

<script>
import FacturasForm from "../../components/facturas/FacturasForm.vue";

export default {
    name: "FacturasCreate",

    components: {
        FacturasForm
    }
};
</script>

<style>
</style>
