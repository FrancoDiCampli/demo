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
            <PresupuestosForm></PresupuestosForm>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapMutations } from "vuex";

// Components
import PresupuestosForm from "../../components/presupuestos/PresupuestosForm.vue";

export default {
    name: "PresupuestosCreate",

    components: {
        PresupuestosForm
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
