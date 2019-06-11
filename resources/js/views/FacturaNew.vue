<template>
    <div>
        <v-card>
            <v-card-text>
                <FacturasForm></FacturasForm>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
//Components
import FacturasForm from "../components/facturas/FacturasForm.vue";

export default {
    name: "FacturaNew",

    components: {
        FacturasForm
    }
};
</script>

<style>
</style>
