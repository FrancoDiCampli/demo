<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
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
