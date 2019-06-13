<template>
    <div>
        <v-card>
            <!-- <FacturasFormUnique></FacturasFormUnique> -->
            <FacturasFormMultiple></FacturasFormMultiple>
        </v-card>
    </div>
</template>

<script>
import FacturasFormUnique from "../components/facturas/FacturasFormUnique.vue";
import FacturasFormMultiple from "../components/facturas/FacturasFormMultiple.vue";

export default {
    name: "FacturaNew",

    components: {
        FacturasFormUnique,
        FacturasFormMultiple
    }
};
</script>

<style>
</style>
