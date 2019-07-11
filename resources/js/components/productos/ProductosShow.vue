<template>
    <div>
        <v-layout row>
            <v-flex xs12>
                <v-card>
                    <div v-show="mode == 'edit'"></div>
                    <div v-show="mode == 'show'">
                        <v-img
                            class="hidden-sm-and-up"
                            :src="showData.articulo.foto"
                            height="250px"
                        >
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
                                            <v-list-tile @click="mode = 'delete'">
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
                                    <v-list-tile @click="mode = 'delete'">
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
                        <div v-if="mode == 'show'">
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
                                            <ProductosShowInventario></ProductosShowInventario>
                                        </v-tab-item>
                                    </v-tabs>
                                </div>
                            </template>
                        </div>
                        <div v-else-if="mode == 'delete'">
                            <v-alert :value="true" color="error">
                                <h2 class="text-xs-center">¿Estas Seguro?</h2>
                                <br />
                                <v-divider dark></v-divider>
                                <br />
                                <p class="text-xs-center">¿Realmente deseas eliminar este Producto?</p>
                                <p class="text-xs-center">Este Cambio es Irreversible</p>
                                <br />
                                <v-layout justify-center>
                                    <v-btn
                                        @click="mode = 'show'"
                                        :disabled="process"
                                        class="elevation-0 red--text"
                                        color="white"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :loading="process"
                                        :disabled="process"
                                        @click="deleteProducto()"
                                        outline
                                        color="white"
                                    >Eliminar</v-btn>
                                </v-layout>
                            </v-alert>
                        </div>
                    </div>
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
import { mapState, mapActions } from "vuex";

export default {
    name: "ProductosShow",

    data() {
        return {
            process: false,
            mode: "show"
        };
    },

    components: {
        ProductosShowData,
        ProductosShowInventario
    },

    computed: {
        ...mapState("crudx", ["showData"])
    },

    methods: {
        ...mapActions("crudx", ["index", "destroy"]),

        deleteProducto: async function() {
            this.process = true;
            await this.destroy({
                url: "/api/articulos/" + this.showData.articulo.id
            });
            await this.index({ url: "/api/articulos" });
            this.mode = "show";
            this.process = false;
            this.$router.push("/productos");
        }
    }
};
</script>

<style>
</style>
