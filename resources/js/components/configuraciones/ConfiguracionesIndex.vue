<template>
    <div>
        <div v-for="standard in data.standard" :key="standard.id">
            <v-layout wrap>
                <v-flex xs12 mb-3>
                    <h2>{{ standard.name }}</h2>
                </v-flex>
                <v-flex xs12 mb-5>
                    <v-expansion-panel>
                        <v-expansion-panel-content
                            v-for="config in standard.configuraciones"
                            :key="config.id"
                            expand-icon="fas fa-caret-down"
                        >
                            <template v-slot:header>
                                <div>{{config.name}}</div>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <v-layout justify-center>
                                        <v-flex xs12 px-2>
                                            <v-layout justify-space-between>
                                                <p>{{config.value}}</p>
                                                <v-btn
                                                    flat
                                                    icon
                                                    color="primary"
                                                    style="margin-top: -10px;"
                                                >
                                                    <v-icon size="medium">fas fa-pen</v-icon>
                                                </v-btn>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                    <v-divider></v-divider>
                                    <v-layout>
                                        <v-flex xs12 px-2>
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

        <v-layout justify-center>
            <v-btn
                v-show="!configAvanzada"
                @click="configAvanzada = true"
                color="primary"
                flat
            >Configuración Avanzada</v-btn>
        </v-layout>

        <div v-for="avanzada in data.avanzada" :key="avanzada.id" v-show="configAvanzada">
            <v-layout wrap>
                <v-flex xs12 mb-3>
                    <h2>{{ avanzada.name }}</h2>
                </v-flex>
                <v-flex xs12 mb-5>
                    <v-expansion-panel>
                        <v-expansion-panel-content
                            v-for="config in avanzada.configuraciones"
                            :key="config.id"
                            expand-icon="fas fa-caret-down"
                        >
                            <template v-slot:header>
                                <div>{{config.name}}</div>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <v-layout justify-center>
                                        <v-flex xs12 px-2>
                                            <v-layout justify-space-between>
                                                <p>{{config.value}}</p>
                                                <v-btn
                                                    flat
                                                    icon
                                                    color="primary"
                                                    style="margin-top: -10px;"
                                                >
                                                    <v-icon size="medium">fas fa-pen</v-icon>
                                                </v-btn>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                    <v-divider></v-divider>
                                    <v-layout>
                                        <v-flex xs12 px-2>
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
//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "ConfiguracionesIndex",

    data() {
        return {
            configAvanzada: false
        };
    },

    computed: {
        ...mapState("crudx", ["data"])
    },

    mounted() {
        this.getConfiguraciones();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getConfiguraciones: async function() {
            let response = await this.index({ url: "/api/configuracion" });
            console.log(response);
        }
    }
};
</script>

<style>
</style>
