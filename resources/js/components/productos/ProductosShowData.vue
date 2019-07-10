<template>
    <v-container fluid grid-list-md>
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm5>
                <v-card class="elevation-0">
                    <v-card-title>
                        <h4>Datos Generales</h4>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Articulo:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.articulo }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Descripción:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.descripcion }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Codigo:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.codarticulo }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Codigo del Proveedor:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.codprov }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Categoria:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.categoria_id }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Marca:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.marca_id }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-divider vertical></v-divider>
            <v-flex xs12 sm5>
                <v-card class="elevation-0">
                    <v-card-title>
                        <h4>Datos Financieros</h4>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Costo:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >$ {{ showData.articulo.costo }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Utilidades:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.utilidades }} %</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Alicuota:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >{{ showData.articulo.alicuota }} %</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile style="margin: 10px 0px;">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <b>Precio:</b>
                                </v-list-tile-title>
                                <v-list-tile-sub-title
                                    class="black--text"
                                >$ {{ showData.articulo.precio }}</v-list-tile-sub-title>
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
    name: "ProductosShowData",

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>

<style>
</style>
