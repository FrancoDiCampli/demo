<template>
    <div v-if="standard != null">
        <v-snackbar color="primary" v-model="snackbarStandard" :timeout="6000" right top>
            Datos Actualizados
            <v-btn color="white" flat @click="snackbarStandard = false" icon>
                <v-icon>fas fa-times</v-icon>
            </v-btn>
        </v-snackbar>
        <div v-for="stan in standard" :key="stan.id">
            <v-layout wrap>
                <v-flex xs12 mb-3>
                    <h2>{{ stan.name }}</h2>
                </v-flex>
                <v-flex xs12 mb-5>
                    <v-expansion-panel>
                        <v-expansion-panel-content
                            v-for="config in stan.configuraciones"
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
                                                                @click="updateConfig()"
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
                                    <div v-else-if="config.type == 'img'">
                                        <v-layout justify-center>
                                            <v-flex>
                                                <v-layout justify-end>
                                                    <croppa
                                                        v-model="logo"
                                                        :width="230"
                                                        :height="230"
                                                        placeholder="Logo"
                                                        placeholder-color="#000"
                                                        :placeholder-font-size="24"
                                                        canvas-color="transparent"
                                                        :show-remove-button="false"
                                                        :show-loading="true"
                                                        :loading-size="25"
                                                        :prevent-white-space="true"
                                                        :zoom-speed="10"
                                                        initial-image="images/logo.png"
                                                    ></croppa>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex>
                                                <v-layout column>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="logo.zoomIn()"
                                                    >
                                                        <v-icon>fas fa-search-plus</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="logo.zoomOut()"
                                                    >
                                                        <v-icon>fas fa-search-minus</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="logo.rotate()"
                                                    >
                                                        <v-icon>fas fa-redo-alt</v-icon>
                                                    </v-btn>
                                                    <div v-if="logo != null">
                                                        <v-btn
                                                            v-show="logo.hasImage()"
                                                            flat
                                                            icon
                                                            color="primary"
                                                            @click="logo.remove()"
                                                        >
                                                            <v-icon>fas fa-times</v-icon>
                                                        </v-btn>
                                                        <v-btn
                                                            v-show="!logo.hasImage()"
                                                            flat
                                                            icon
                                                            color="primary"
                                                            @click="logo.chooseFile()"
                                                        >
                                                            <v-icon>fas fa-plus</v-icon>
                                                        </v-btn>
                                                    </div>
                                                    <div v-if="logo != null">
                                                        <v-btn :disabled="!logo.hasImage()" flat icon color="primary" @click="updateConfig()">
                                                            <v-icon size="medium">fas fa-pen</v-icon>
                                                        </v-btn>
                                                    </div>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
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
//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "ConfiguracionesStandard",

    data() {
        return {
            standard: null,
            logo: null,
            snackbarStandard: false
        };
    },

    mounted() {
        this.getConfigStandard();
    },

    methods: {
        ...mapMutations('crudx', ['fillForm']),
        ...mapActions("crudx", ["index", 'update']),

        getConfigStandard: async function() {
            let response = await this.index({ url: "/api/configuracion" });
            this.standard = response.standard;
        },

        updateConfig: async function() {
            let formularioStandard = {
                provincia:          this.standard[0].configuraciones[0].value,
                localidad:          this.standard[0].configuraciones[1].value,
                codigopostal:       this.standard[0].configuraciones[2].value,
                direccion:          this.standard[0].configuraciones[3].value,
                telefono:           this.standard[0].configuraciones[4].value,
                email:              this.standard[0].configuraciones[5].value,
                nombrefantasia:     this.standard[1].configuraciones[0].value,
                tagline:            this.standard[1].configuraciones[1].value,
                logo:               this.logo.generateDataUrl(),
                domiciliocomercial: this.standard[1].configuraciones[3].value
            };

            await this.fillForm(formularioStandard);
            await this.update({url: '/api/configuracion/1'});

            this.snackbarStandard = true;
        },
    }
};
</script>

<style>
</style>
