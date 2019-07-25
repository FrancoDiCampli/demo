<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <div v-if="showData.proveedor">
            <ProveedoresShow></ProveedoresShow>
        </div>
    </div>
</template>

<script>
// Components
import ProveedoresShow from "../../components/proveedores/ProveedoresShow.vue";

// Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "ShowProveedores",

    props: ["id"],

    components: {
        ProveedoresShow
    },

    computed: {
        ...mapState("crudx", ["showData"])
    },

    mounted() {
        this.show({ url: "/api/suppliers/" + this.id });
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["show"]),

        goBack() {
            this.resetForm();
            this.$router.push("/proveedores");
        }
    }
};
</script>

<style>
</style>