<template>
    <div>
        <template>
            <!-- Barra de progreso circular -->
            <div class="loading" v-show="inProcess || process">
                <v-layout justify-center>
                    <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                </v-layout>
            </div>
        </template>
        <v-card v-show="!process">
            <v-tooltip left>
                <template v-slot:activator="{ on }">
                    <v-btn dark fab fixed right bottom @click="goBack()" color="primary" v-on="on">
                        <v-icon>fas fa-chevron-left</v-icon>
                    </v-btn>
                </template>
                <span>Volver</span>
            </v-tooltip>
            <!-- Header -->
            <v-card-text>
                <v-layout justify-space-between>
                    <h2>Nuevo Cliente</h2>
                </v-layout>
            </v-card-text>
            <v-divider></v-divider>
            <!-- Body -->
            <v-card-text>
                <!-- Formulario para agregar un cliente -->
                <v-form ref="clientesForm" @submit.prevent="saveCliente">
                    <!-- Componente Formulario -->
                    <ClientesForm mode="create"></ClientesForm>
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
import ClientesForm from "../../components/clientes/ClientesForm.vue";

export default {
    name: "ClienteCreate",

    data() {
        return {
            process: false
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess"])
    },

    components: {
        ClientesForm
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["index", "save"]),

        saveCliente: async function() {
            if (this.$refs.clientesForm.validate()) {
                await this.save({ url: "/api/clientes" });
                await this.index({ url: "/api/clientes" });
                this.$refs.clientesForm.resetValidation();
                this.$refs.clientesForm.reset();
                this.$router.push("/clientes");
            }
        },

        goBack() {
            this.resetForm();
            this.$router.push("/clientes");
        }
    }
};
</script>
