<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <v-card-text>
                <div>
                    <v-btn @click="log()">log</v-btn>
                    <ProductosShow></ProductosShow>
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
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
