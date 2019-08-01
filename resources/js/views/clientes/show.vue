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

        <div v-if="showData.cliente">
            <ClientesShow></ClientesShow>
        </div>
    </div>
</template>

<script>
// Vuex
import { mapActions, mapState, mapMutations } from "vuex";

// Components
import ClientesShow from "../../components/clientes/ClientesShow.vue";
export default {
    name: "ShowClientes",

    props: ["id"],

    components: {
        ClientesShow
    },

    computed: {
        ...mapState("crudx", ["showData"])
    },

    mounted() {
        this.show({ url: "/api/clientes/" + this.id });
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["show"]),

        goBack() {
            this.resetForm();
            this.$router.push("/clientes");
        }
    }
};
</script>

<style>
</style>
