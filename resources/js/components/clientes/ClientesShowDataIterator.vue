<template>
    <v-container fluid grid-list-md>
        <v-layout wrap>
            <v-flex xs12 sm6>
                <v-data-iterator :items="personalData" content-tag="v-layout" hide-actions row wrap>
                    <template v-slot:item="props">
                        <v-flex xs12>
                            <v-card height="250">
                                <v-list dense>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Apellido y Nombre:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.razonsocial }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>CUIT:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{ showData.cliente.cuit }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Condición IVA:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.condicioniva }}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </template>
                </v-data-iterator>
            </v-flex>
            <v-flex xs12 sm6>
                <v-data-iterator :items="contactData" content-tag="v-layout" hide-actions row wrap>
                    <template v-slot:item="props">
                        <v-flex xs12>
                            <v-card height="250">
                                <v-list dense>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>TEL/CEL:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.telefono }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Email:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{ showData.cliente.email }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Direccón:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.direccion }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Codigo Postal:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.codigopostal }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Provincia:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.provincia }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Localidad:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.cliente.localidad }}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                    </template>
                </v-data-iterator>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
// Vuex
import { mapState } from "vuex";

export default {
    name: "CLientesShowDataIterator",

    data() {
        return {
            personalData: [{ name: "Datos Personales" }],
            contactData: [{ name: "Contacto" }]
        };
    },

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>
