<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm4 mx-1>
                <v-text-field label="Cuit" hint="Cuit" box single-line @keyup.40="find()"></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-text-field disabled label="Razón Social" hint="Razón Social" box single-line></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-select
                    :items="condiciones"
                    v-model="condicion"
                    disabled
                    box
                    single-line
                    label="Condición"
                    hint="Condición"
                ></v-select>
            </v-flex>
        </v-layout>

        <v-dialog v-model="findClienteDialog" width="750" persistent>
            <v-card>
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>Buscar Cliente</h2>
                        <!-- Boton cerrar modal -->
                        <v-btn
                            @click="FindClientesDialog()"
                            flat
                            icon
                            style="margin: 0; padding: 0;"
                        >
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <FindCliente></FindCliente>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
//Vuex
import { mapState, mapMutations } from "vuex";

//Components
import FindCliente from "./FindCliente.vue";

export default {
    name: "FacturasForm",

    data() {
        return {
            condiciones: ["Contado", "Credito - Debito", "Cuenta Corriente"],
            condicion: "Contado"
        };
    },

    components: {
        FindCliente
    },

    computed: {
        ...mapState(["findClienteDialog"])
    },

    methods: {
        ...mapMutations(["FindClientesDialog"]),
        find() {
            this.FindClientesDialog();
        }
    }
};
</script>

<style>
</style>
