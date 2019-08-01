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
            <RemitosForm></RemitosForm>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapMutations } from "vuex";

// Components
import RemitosForm from "../../components/remitos/RemitosForm.vue";

export default {
    name: "ComprasCreate",

    components: {
        RemitosForm
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
