<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <FacturasForm></FacturasForm>
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
