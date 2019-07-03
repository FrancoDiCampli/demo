<template>
    <div>
        <v-btn dark fab fixed right bottom @click="$router.push('/clientes')" color="primary">
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
import { mapActions, mapState } from "vuex";

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
        ...mapActions("crudx", ["show"])
    }
};
</script>

<style>
</style>
