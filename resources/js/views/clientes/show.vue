<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <v-card-text>
                <div v-if="showData.cliente">
                    <ClientesShow></ClientesShow>
                </div>
            </v-card-text>
        </v-card>
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
