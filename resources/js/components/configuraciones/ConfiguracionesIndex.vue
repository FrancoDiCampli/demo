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
            <v-expansion-panel>
                <v-expansion-panel-content v-for="config in configuraciones" :key="config.alias">
                    <template v-slot:header>
                        <div>
                            <b>{{ config.alias }}</b>
                        </div>
                    </template>
                    <v-card>
                        <v-card-text>{{config.value}}</v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
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
            configuraciones: [],
            headers: [
                { text: "CUIT", sortable: false },
                { text: "RAZON SOCIAL", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },
    computed: {
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.getConfiguraciones();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getConfiguraciones: async function() {
            let response = await this.index({ url: "/api/configuracion" });

            for (const config in response) {
                let objet = {
                    alias: config,
                    value: response[config]
                };

                this.configuraciones.push(objet);
            }
        }
    }
};
</script>

<style>
</style>
