<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <FormularioPrueba></FormularioPrueba>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapMutations } from "vuex";

// Components
import RemitosForm from "../../components/remitos/RemitosForm.vue";
import FormularioPrueba from "../../components/remitos/FormularioPrueba.vue";

export default {
    name: "ComprasCreate",

    components: {
        RemitosForm,
        FormularioPrueba
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),

        goBack() {
            this.resetForm();
            this.$router.push("/compras");
        }
    }
};
</script>

<style>
</style>
