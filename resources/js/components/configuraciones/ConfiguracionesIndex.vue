<template>
    <div>
        <v-card>
            <v-container fluid grid-list-md>
                <template v-for="(item) in data">
                    <v-list two-line :key="item">
                        <v-divider></v-divider>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>CUIT</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.cuit"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Razón Social</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.razonsocial"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Dirección</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.direccion"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Localidad</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.localidad"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Provincia</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.provincia"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Código Postal</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.codigopostal"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Teléfono</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.telefono"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Email</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.email"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Inicio Actividades</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.inicioactividades"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Nombre Fantasia</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.nombrefantasia"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Domicilio Comercial</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.domiciliocomercial"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Leyenda</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.tagline"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Email</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.email"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                <b>Punto de Venta Nº.</b>
                            </v-list-tile-title>
                            <v-list-tile-sub-title v-html="item.puntoventa"></v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-divider></v-divider>
                    </v-list>
                </template>
            </v-container>
        </v-card>
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
        this.index({ url: "/api/configuracion" });
    },

    methods: {
        ...mapActions("crudx", ["index"])
    }
};
</script>

<style>
</style>
