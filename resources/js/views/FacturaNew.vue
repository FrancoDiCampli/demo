<template>
    <div>
        <v-card>
            <v-card-text>
                <h2>Resumen</h2>
                <!-- <FacturasForm></FacturasForm> -->
                <FacturasResumen></FacturasResumen>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
//Components
// import FacturasForm from "../components/facturas/FacturasForm.vue";
import FacturasResumen from "../components/facturas/FacturasResumen.vue";
export default {
    name: "FacturaNew",

    components: {
        FacturasResumen
    }
};
</script>

<style>
</style>
