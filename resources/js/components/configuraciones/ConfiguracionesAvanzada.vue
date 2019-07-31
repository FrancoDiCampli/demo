<template>
    <div>
        <v-snackbar color="primary" v-model="snackbarAvanzada" :timeout="6000" right top>
            Datos Actualizados
            <v-btn color="white" flat @click="snackbarAvanzada = false" icon>
                <v-icon>fas fa-times</v-icon>
            </v-btn>
        </v-snackbar>
        <div v-for="avan in avanzada" :key="avan.id">
            <v-layout wrap>
                <v-flex xs12 mb-3>
                    <h2>{{ avan.name }}</h2>
                </v-flex>
                <v-flex xs12 mb-5>
                    <v-expansion-panel>
                        <v-expansion-panel-content
                            v-for="config in avan.configuraciones"
                            :key="config.id"
                            expand-icon="fas fa-caret-down"
                        >
                            <template v-slot:header>
                                <div>{{ config.name }}</div>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <div v-if="config.type == 'text' || config.type == 'number'">
                                        <v-layout justify-space-around>
                                            <v-flex xs12>
                                                <v-layout justify-space-between>
                                                    <v-flex xs10 sm4>
                                                        <v-layout justify-start>
                                                            <v-text-field
                                                                v-model="config.value"
                                                                :type="config.type == 'number' ? 'number' : 'text'"
                                                                :label="config.name"
                                                                box
                                                            ></v-text-field>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs2 s8>
                                                        <v-layout justify-end>
                                                            <v-btn
                                                                @click="updateAdvanceConfig()"
                                                                color="primary"
                                                                flat
                                                                icon
                                                            >
                                                                <v-icon size="medium">fas fa-pen</v-icon>
                                                            </v-btn>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                    <div v-else-if="config.type == 'select'">
                                        <v-layout justify-space-around>
                                            <v-flex xs12>
                                                <v-layout justify-space-between>
                                                    <v-flex xs10 sm4>
                                                        <v-layout justify-start>
                                                            <v-select
                                                                v-model="config.value"
                                                                :items="config.items"
                                                                :label="config.name"
                                                                box
                                                            ></v-select>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs2 s8>
                                                        <v-layout justify-end>
                                                            <v-btn
                                                                @click="updateAdvanceConfig()"
                                                                color="primary"
                                                                flat
                                                                icon
                                                            >
                                                                <v-icon size="medium">fas fa-pen</v-icon>
                                                            </v-btn>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                    <div v-else-if="config.type == 'file'">
                                        <div
                                            v-if="avanzada[0].configuraciones[1].value != null && avanzada[0].configuraciones[1].value != ''"
                                        >
                                            <ConfiguracionesCertificados
                                                :valueCert="config.valueCert"
                                                :valueKey="config.valueKey"
                                            ></ConfiguracionesCertificados>
                                        </div>
                                        <div v-else>
                                            <v-alert :value="true" color="error">
                                                <div
                                                    class="text-xs-center"
                                                >Es necesario registrar el CUIT del titular antes de cargar los certificados.</div>
                                            </v-alert>
                                        </div>
                                    </div>
                                    <v-divider></v-divider>
                                    <v-layout>
                                        <v-flex>
                                            <br />
                                            {{ config.msg }}
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-flex>
            </v-layout>
        </div>
    </div>
</template>

<script>
// Components
import ConfiguracionesCertificados from "./ConfiguracionesCertificados.vue";

// Axios
import axios from "axios";
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("accsess_token");

export default {
    name: "ConfiguracionesAvanzadas",

    data() {
        return {
            avanzada: null,
            snackbarAvanzada: false
        };
    },

    components: {
        ConfiguracionesCertificados
    },

    mounted() {
        this.getConfigAvanzada();
    },

    methods: {
        getConfigAvanzada() {
            axios
                .get("/api/config/advance")
                .then(response => {
                    this.avanzada = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        updateAdvanceConfig: async function() {
            let formularioAvanzado = {
                cuit: this.avanzada[0].configuraciones[1].value,
                razonsocial: this.avanzada[0].configuraciones[2].value,
                condicioniva: this.avanzada[0].configuraciones[3].value,
                inicioactividades: this.avanzada[0].configuraciones[4].value,
                numfactura: this.avanzada[1].configuraciones[0].value,
                numpresupuesto: this.avanzada[1].configuraciones[1].value,
                numrecibo: this.avanzada[1].configuraciones[2].value
            };

            axios
                .post("/api/config/update/advance", formularioAvanzado)
                .then(response => {
                    this.snackbarAvanzada = true;
                    this.getConfigAvanzada();
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>

<style>
</style>