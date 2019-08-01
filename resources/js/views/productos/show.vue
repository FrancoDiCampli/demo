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
        <!-- <v-btn @click="log()">log</v-btn> -->
        <div v-if="showData.articulo">
            <ProductosShow></ProductosShow>
        </div>
    </div>
</template>

<script>
// axios
import axios from "axios";

// Vuex
import { mapActions, mapState, mapMutations } from "vuex";

// Components
import ProductosShow from "../../components/productos/ProductosShow.vue";
export default {
    name: "ShowProductos",

    props: ["id"],

    components: {
        ProductosShow
    },

    computed: {
        ...mapState("crudx", ["showData"])
    },

    mounted() {
        this.show({ url: "/api/articulos/" + this.id });
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["show"]),

        goBack() {
            this.resetForm();
            this.$router.push("/productos");
        },

        log() {
            console.log(this.showData);
        }
    }
};
</script>

<style>
</style>
