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
            <FacturasForm mode="create"></FacturasForm>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapMutations } from "vuex";

// Components
import FacturasForm from "../../components/facturas/FacturasForm.vue";

export default {
    name: "FacturasCreate",

    components: {
        FacturasForm
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),

        goBack() {
            this.resetForm();
            this.$router.push("/ventas");
        }
    }
};
</script>

<style>
</style>
