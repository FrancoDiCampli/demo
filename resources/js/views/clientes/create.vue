<template>
    <v-card>
        <v-btn dark fab fixed right bottom @click="$router.push('/clientes')" color="primary">
            <v-icon>fas fa-chevron-left</v-icon>
        </v-btn>
        <!-- Header -->
        <v-card-text>
            <v-layout justify-space-between>
                <h2>Nuevo Cliente</h2>
            </v-layout>
        </v-card-text>
        <v-divider></v-divider>
        <!-- Body -->
        <v-card-text>
            <template>
                <!-- Barra de progreso circular -->
                <div class="loading" v-show="inProcess">
                    <v-layout justify-center>
                        <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                    </v-layout>
                </div>
            </template>
            <!-- Formulario para agregar un cliente -->
            <v-form ref="clientesForm" @submit.prevent="saveCliente">
                <!-- Componente Formulario -->
                <ClientesForm></ClientesForm>
                <v-layout justify-center>
                    <v-btn :disabled="inProcess" type="submit" color="primary">Guardar</v-btn>
                </v-layout>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import ClientesForm from "../../components/clientes/ClientesForm.vue";

export default {
    name: "ClienteCreate",

    computed: {
        ...mapState("crudx", ["inProcess"])
    },

    components: {
        ClientesForm
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        saveCliente: async function() {
            if (this.$refs.clientesForm.validate()) {
                await this.save({ url: "/api/clientes" });
                await this.index({ url: "/api/clientes" });
                this.$refs.clientesForm.resetValidation();
                this.$refs.clientesForm.reset();
                this.$router.push("/clientes");
            }
        }
    }
};
</script>

<style>
</style>
