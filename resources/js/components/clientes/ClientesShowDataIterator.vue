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
                                        >{{ showData.razonsocial }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>CUIT:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{ showData.cuit }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Condición IVA:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.condicioniva }}</v-list-tile-content>
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
                                        >{{ showData.telefono }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Email:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{ showData.email }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Direccón:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.direccion }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Codigo Postal:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.codigopostal }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Provincia:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.provincia }}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <b>Localidad:</b>
                                        </v-list-tile-content>
                                        <v-list-tile-content
                                            class="align-end"
                                        >{{ showData.localidad }}</v-list-tile-content>
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
