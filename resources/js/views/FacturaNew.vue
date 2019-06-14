<template>
    <div>
        <v-card>
            <FacturasUnique></FacturasUnique>
        </v-card>
    </div>
</template>

<script>
import FacturasForm from "../components/facturas/FacturasForm.vue";
import FacturasUnique from "../components/facturas/FacturasUnique.vue";

export default {
    name: "FacturaNew",

    components: {
        FacturasForm,
        FacturasUnique
    }
};
</script>

<style>
</style>
