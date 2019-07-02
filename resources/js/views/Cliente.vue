<template>
    <div>
        <!-- Boton para agregar un nuevo cliente -->
        <v-btn
            dark
            fab
            fixed
            right
            bottom
            @click="createClientesDialog = !createClientesDialog"
            color="primary"
        >
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
        <!-- Modal nuevo cliente -->
        <v-dialog v-model="createClientesDialog" width="750" persistent>
            <v-card>
                <!-- Modal Header -->
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>Nuevo Cliente</h2>
                        <!-- Boton cerrar modal -->
                        <v-btn
                            @click="createClientesDialog = false; $refs.clientesForm.reset();"
                            flat
                            icon
                            style="margin: 0; padding: 0;"
                        >
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <!-- Modal body -->
                <v-card-text>
                    <template>
                        <!-- Barra de progreso circular -->
                        <div class="loading" v-show="inProcess">
                            <v-layout justify-center>
                                <v-progress-circular
                                    :size="70"
                                    :width="7"
                                    color="primary"
                                    indeterminate
                                ></v-progress-circular>
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
        </v-dialog>

        <!-- Index Cliente -->
        <v-card>
            <v-card-text>
                <ClientesIndex></ClientesIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
// Vuex
import { mapState, mapActions } from "vuex";

// Components
import ClientesIndex from "../components/clientes/ClientesIndex.vue";
import ClientesForm from "../components/clientes/ClientesForm.vue";

export default {
    name: "Cliente",

    data() {
        return {
            createClientesDialog: false
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess"])
    },

    components: {
        ClientesIndex,
        ClientesForm
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        saveCliente: async function() {
            if (this.$refs.clientesForm.validate()) {
                await this.save({ url: "api/clientes" });
                await this.index({ url: "api/clientes" });
                this.$refs.clientesForm.reset();
                this.createClientesDialog = false;
            }
        }
    }
};
</script>

<style>
</style>
