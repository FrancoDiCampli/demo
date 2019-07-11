<template>
    <div>
        <v-layout row>
            <v-flex xs12>
                <v-card>
                    <v-img class="hidden-sm-and-up" :src="showData.articulo.foto" height="250px">
                        <v-layout column fill-height>
                            <v-card-title>
                                <v-spacer></v-spacer>

                                <v-menu style="margin: 15px 0px;">
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            absolute
                                            right
                                            flat
                                            icon
                                            dark
                                            color="white"
                                            v-on="on"
                                        >
                                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-tile>
                                            <v-list-tile-title>Editar</v-list-tile-title>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-title>Eliminar</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </v-card-title>

                            <v-spacer></v-spacer>

                            <v-card-title>
                                <v-layout justify-center wrap>
                                    <v-flex xs12>
                                        <h1
                                            class="text-xs-center white--text"
                                        >{{ showData.articulo.articulo }}</h1>
                                    </v-flex>
                                    <v-flex xs12>
                                        <h3
                                            class="text-xs-center white--text"
                                        >{{ showData.articulo.descripcion }}</h3>
                                    </v-flex>
                                </v-layout>
                            </v-card-title>
                        </v-layout>
                    </v-img>
                    <div class="hidden-xs-only">
                        <br />
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn absolute right flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                        <v-flex xs12>
                            <v-layout justify-center>
                                <v-avatar size="200">
                                    <img :src="showData.articulo.foto" />
                                </v-avatar>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12>
                            <br />
                            <h1
                                class="text-xs-center primary--text"
                            >{{ showData.articulo.articulo }}</h1>
                        </v-flex>
                        <v-flex xs12>
                            <h3
                                class="text-xs-center primary--text"
                            >{{ showData.articulo.descripcion }}</h3>
                        </v-flex>
                    </div>
                    <br />
                    <template>
                        <div>
                            <v-tabs
                                fixed-tabs
                                grow
                                slider-color="primary"
                                active-class="primary--text"
                            >
                                <v-tab>Datos</v-tab>
                                <v-tab>Inventarios</v-tab>
                                <v-tab-item>
                                    <div>
                                        <ProductosShowData></ProductosShowData>
                                    </div>
                                </v-tab-item>
                                <v-tab-item>
                                    <div>
                                        <ProductosShowInventario></ProductosShowInventario>
                                    </div>
                                </v-tab-item>
                            </v-tabs>
                        </div>
                    </template>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Components
import ProductosShowData from "./ProductosShowData.vue";
import ProductosShowInventario from "./ProductosShowInventario.vue";

// Vuex
import { mapState } from "vuex";

export default {
    name: "ProductosShow",

    components: {
        ProductosShowData,
        ProductosShowInventario
    },

    computed: {
        ...mapState("crudx", ["showData"])
    }
};
</script>

<style>
</style>
