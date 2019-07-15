<template>
    <div>
        <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <v-card>
            <v-card-text>
                <RemitosForm></RemitosForm>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapMutations } from "vuex";
import RemitosForm from "../../components/proveedores/RemitoForm.vue";
export default {
    name: "RemitosCreate",

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
