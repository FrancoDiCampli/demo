<template>
    <div>
        <v-container fluid grid-list-md>
            <v-layout justify-end>
                <v-menu>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            flat
                            icon
                            dark
                            color="primary"
                            v-on="on"
                            @click="$router.push('/configuraciones/actualizar');"
                        >
                            <v-icon size="high">fas fa-tools</v-icon>
                        </v-btn>
                    </template>
                </v-menu>
            </v-layout>
            <v-card>
                <v-layout justify-center>
                    <h1>Configuración</h1>
                </v-layout>
                <v-divider></v-divider>
                <v-expansion-panel>
                    <v-expansion-panel-content v-for="item in configStandard" :key="item.config">
                        <template v-slot:header>
                            <div>
                                <b>{{ item.value.config }}</b>
                            </div>
                        </template>
                        <v-card>
                            <v-card-text>{{item.value.value}}</v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <div v-show="rol == 'superAdmin'">
                    <v-layout justify-center>
                        <h1>Avanzada</h1>
                    </v-layout>
                    <v-divider></v-divider>
                    <v-expansion-panel>
                        <v-expansion-panel-content v-for="(item, i) in configAvanzada" :key="i">
                            <template v-slot:header>
                                <div>
                                    <b>{{ item.value.config }}</b>
                                </div>
                            </template>
                            <v-card>
                                <v-card-text>{{ item.value.value }}</v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </div>
            </v-card>
        </v-container>
    </div>
</template>

<script>
//Axios
import axios from "axios";
//Vuex
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    name: "ConfiguracionesIndex",
    data() {
        return {
            configAvanzada: [],
            configStandard: [],
            headers: [
                { text: "CUIT", sortable: false },
                { text: "RAZON SOCIAL", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },
    computed: {
        ...mapState("crudx", ["data", "inProcess"]),
        ...mapState("auth", ["rol", "token"])
    },

    mounted() {
        this.getConfiguraciones();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getConfiguraciones: async function() {
            let response = await this.index({ url: "/api/configuracion" });
            let configAvan = response.avanzada;
            let configStan = response.standard;

            for (const confi in configAvan) {
                let objet = {
                    config: confi,
                    value: configAvan[confi]
                };

                this.configAvanzada.push(objet);
            }

            for (const conf in configStan) {
                let objet = {
                    config: conf,
                    value: configStan[conf]
                };

                this.configStandard.push(objet);
            }
        }
    }
};
</script>

<style>
</style>
