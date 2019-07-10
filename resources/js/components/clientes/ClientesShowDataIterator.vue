<template>
    <v-container fluid grid-list-md>
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm5>
                <v-card class="elevation-0">
                    <v-card-title>
                        <h4>Datos Personales</h4>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Apellido y Nombre:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.razonsocial }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>CUIT:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.documentounico }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Condición IVA:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.condicioniva }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-divider vertical></v-divider>
            <v-flex xs12 sm5>
                <v-card class="elevation-0">
                    <v-card-title>
                        <h4>Contacto</h4>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>TEL/CEL:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.telefono }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Email:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.email }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Dirección:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.direccion }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Codigo Postal:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.codigopostal }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Provincia:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.provincia }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Localidad:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.cliente.localidad }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "CLientesShowDataIterator",

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>
