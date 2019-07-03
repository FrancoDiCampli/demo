<template>
    <div>
        <v-card>
            <FacturasForm></FacturasForm>
        </v-card>
    </div>
</template>

<script>
import FacturasForm from "../../components/facturas/FacturasForm.vue";

export default {
    name: "FacturaNew",

    components: {
        FacturasForm
    }
};
</script>

<style>
</style>
