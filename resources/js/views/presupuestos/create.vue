<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
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
