<template>
    <div>
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn dark fab fixed right bottom @click="goBack()" color="primary" v-on="on">
                    <v-icon>fas fa-chevron-left</v-icon>
                </v-btn>
            </template>
            <span>Volver</span>
        </v-tooltip>
        <v-card>
            <FacturasForm mode="edit"></FacturasForm>
        </v-card>
    </div>
</template>

<script>
// Components
import FacturasForm from "../../components/facturas/FacturasForm.vue";

// Vuex
import { mapMutations } from "vuex";

export default {
    name: "editFactura",

    components: {
        FacturasForm
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),

        goBack() {
            this.resetForm();
            this.$router.push("/presupuestos");
        }
    }
};
</script>

<style>
</style>
