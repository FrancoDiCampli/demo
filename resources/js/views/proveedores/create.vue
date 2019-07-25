<template>
    <div>
        <v-card>
            <v-btn dark fab fixed right bottom @click="goBack()" color="primary">
                <v-icon>fas fa-chevron-left</v-icon>
            </v-btn>
            <!-- Header -->
            <v-card-text>
                <v-layout justify-space-between>
                    <h2>Nuevo Proveedor</h2>
                </v-layout>
            </v-card-text>
            <v-divider></v-divider>
            <!-- Body -->
            <v-card-text>
                <!-- Formulario para agregar un cliente -->
                <v-form ref="proveedoresForm" @submit.prevent="saveProveedor">
                    <!-- Componente Formulario -->
                    <ProveedoresForm mode="create"></ProveedoresForm>
                    <v-layout justify-center>
                        <v-btn
                            :loading="inProcess"
                            :disabled="inProcess"
                            type="submit"
                            color="primary"
                        >Guardar</v-btn>
                    </v-layout>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions, mapMutations } from "vuex";

// Components
import ProveedoresForm from "../../components/proveedores/ProveedoresForm.vue";

export default {
    name: "ProveedoresCreate",
    computed: {
        ...mapState("crudx", ["inProcess"])
    },

    components: {
        ProveedoresForm
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["index", "save"]),

        saveProveedor: async function() {
            if (this.$refs.proveedoresForm.validate()) {
                await this.save({ url: "/api/suppliers" });
                await this.index({ url: "/api/suppliers" });
                this.$refs.proveedoresForm.resetValidation();
                this.$refs.proveedoresForm.reset();
                this.$router.push("/proveedores");
            }
        },

        goBack() {
            this.resetForm();
            this.$router.push("/proveedores");
        }
    }
};
</script>

