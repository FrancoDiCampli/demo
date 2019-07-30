<template>
    <div v-if="avanzada != null">
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
                                    <div v-else-if="config.type == 'file'">
                                        <ConfiguracionesCertificados
                                            :valueCert="config.valueCert"
                                            :valueKey="config.valueKey"
                                        ></ConfiguracionesCertificados>
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

// Vuex
import { mapState, mapMutations, mapActions } from "vuex";

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
        ...mapMutations("crudx", ["fillForm"]),
        ...mapActions("crudx", ["index", "update"]),

        getConfigAvanzada: async function() {
            let response = await this.index({ url: "/api/configuracion" });
            this.avanzada = response.avanzada;
            console.log(this.avanzada);
        },

        updateConfig: async function() {
            // let formularioStandard = {
            //     provincia:          this.standard[0].configuraciones[0].value,
            //     localidad:          this.standard[0].configuraciones[1].value,
            //     codigopostal:       this.standard[0].configuraciones[2].value,
            //     direccion:          this.standard[0].configuraciones[3].value,
            //     telefono:           this.standard[0].configuraciones[4].value,
            //     email:              this.standard[0].configuraciones[5].value,
            //     nombrefantasia:     this.standard[1].configuraciones[0].value,
            //     tagline:            this.standard[1].configuraciones[1].value,
            //     logo:               this.logo.generateDataUrl(),
            //     domiciliocomercial: this.standard[1].configuraciones[3].value
            // };
            // await this.fillForm(formularioStandard);
            // await this.update({url: '/api/configuracion/1'});
            // this.snackbarStandard = true;
        }
    }
};
</script>

<style>
.fileContainer {
    padding: 8px 32px;
    width: auto;
}

.fileButton {
    overflow: hidden;
    display: inline-block;
    position: relative;
    cursor: pointer;
    width: 100%;
    height: 35px;
    line-height: 35px;
    padding: 0 1.5rem;
    color: #26a69a;
    font-size: 16px;
    font-weight: 600;
    font-family: "Roboto", sans-serif;
    letter-spacing: 0.8px;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    white-space: nowrap;
    outline: none;
    border: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border-radius: 2px;
    transition: all 0.3s ease-out;
    border: solid 0.5px #26a69a;
}

.fileInput {
    cursor: pointer;
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    font-size: 50px;
    opacity: 0;
    -moz-opacity: 0;
    filter: Alpha(opacity=0);
}

.fileContent {
    width: 100%;
    height: 150px;
    margin: 6px;
    text-align: center;
}

.fileIcon {
    margin-top: 16px;
}

.fileName {
    font-size: 16px;
    margin-top: -16px;
}

.card-enter-active,
.card-leave-active {
    transition: all 5s;
}
.card-enter,
.card-leave-to {
    opacity: 0;
    transform: translateY(-30px);
    transition: all 5s;
}
</style>