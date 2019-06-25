<template>
    <v-card>
        <v-card-title>Ventas</v-card-title>
        <v-card-text>
            <v-container fluid>
                <v-layout>
                    <v-flex xs3>
                        <p>Filtros:{{ datos }}</p>
                    </v-flex>
                    <v-flex>
                        <v-switch v-model="datos" label="Fecha" value="fecha"></v-switch>
                    </v-flex>
                    <v-flex>
                        <v-switch v-model="datos" label="Vendedores" value="vendedores"></v-switch>
                    </v-flex>
                    <v-flex>
                        <v-switch v-model="datos" label="producto" value="producto"></v-switch>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-card-text>
            <v-toolbar flat color="white">
                <v-toolbar-title>Expandable Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark @click="traer">Listar</v-btn>
            </v-toolbar>
        </v-card-text>
        <v-card-text>
            <v-layout row wrap v-show="datos.includes('fecha')">
                <v-flex xs12 sm6>
                    <v-date-picker v-model="dates" multiple></v-date-picker>
                </v-flex>
                <v-flex xs12 sm6>
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="dates"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-combobox
                                v-model="dates"
                                multiple
                                chips
                                small-chips
                                label="Multiple picker in menu"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                            ></v-combobox>
                        </template>
                        <v-date-picker v-model="dates" multiple no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                            <v-btn flat color="primary" @click="$refs.menu.save(dates)">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-flex>
            </v-layout>
        </v-card-text>
        <v-spacer></v-spacer>

        <v-layout>
            <!-- Vendedores -->
            <v-flex v-show="datos.includes('vendedores')">
                <v-select
                    v-model="user"
                    :items="usuarios"
                    item-text="name"
                    item-value="id"
                    box
                    chips
                    label="Vendedores"
                    multiple
                ></v-select>
            </v-flex>
            <!-- Articulos -->
            <v-flex xs11 v-show="datos.includes('producto')">
                <!-- Input Buscar Articulos -->
                <v-text-field
                    @keyup="findArticle()"
                    autofocus
                    v-model="article"
                    label="Articulo"
                    box
                    single-line
                ></v-text-field>

                <!-- Tabla Buscar Articulos -->
                <transition name="fade">
                    <v-data-table
                        v-show="article != null && article != '' && products.length > 0"
                        no-data-text="El articulo no se encuentra en la base de datos."
                        hide-actions
                        hide-headers
                        :items="products"
                        class="search-table"
                    >
                        <template v-slot:items="article">
                            <tr
                                @click="articleSelected = article.item.id"
                                @dblclick="selectArticle(article.item)"
                                style="cursor: pointer;"
                                :style="articleSelected == article.item.id ? 'background-color: #26A69A; color: #FFFFFF;' : ''"
                            >
                                <td>{{ article.item.codarticulo }}</td>
                                <td>{{ article.item.articulo }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>
        </v-layout>

        <v-data-table :headers="headers" :items="data" :search="search">
            <template v-slot:items="venta">
                <td>{{ venta.item.id }}</td>
                <td class="text-xs-right">{{ venta.item.numfactura }}</td>
                <td class="text-xs-right">{{ venta.item.fecha }}</td>
                <td class="text-xs-right" v-if="venta.item.pagada==1">Pagada</td>
                <td class="text-xs-right" v-else>No Pagada</td>
                <td class="text-xs-right">{{ venta.item.condicionventa }}</td>
            </template>
            <template v-slot:no-results>
                <v-alert
                    :value="true"
                    color="error"
                    icon="warning"
                >Your search for "{{ search }}" found no results.</v-alert>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";
import { async } from "q";

export default {
    data() {
        return {
            user: [],

            datos: [],
            dates: [],
            menu: false,
            search: "",
            headers: [
                { text: "ID", value: "id", sortable: false },
                { text: "Fact", value: "numfactura", sortable: false },
                { text: "Fecha", value: "fecha", sortable: false },
                { text: "Estado", value: "pagada", sortable: false },
                { text: "Condicion", value: "condicionventa", sortable: false }
            ],
            ventas: [],
            desde: null,
            hasta: null,
            usuarios: [],
            products: [],
            articleSelected: null,
            article: null
        };
    },

    computed: {
        ...mapState("crudx", ["data"])
    },

    watch: {
        dates() {
            this.desde = this.dates[0];
            this.hasta = this.dates[1];
        }
    },
    created() {
        this.traerUsuarios();
    },

    mounted() {
        this.index({
            url: "api/estadisticas/xfecha",
            from: this.desde,
            to: this.hasta
        });
    },

    methods: {
        ...mapActions("crudx", ["index"]),
        traerUsuarios() {
            axios
                .get("api/estadisticas/usuarios")
                .then(response => {
                    this.usuarios = response.data;
                    console.log(this.usuarios[0].name);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        findArticle() {
            this.articleSelected = null;

            axios
                .get("/api/articulos", {
                    params: {
                        buscarArticulo: this.article,
                        limit: 5
                    }
                })
                .then(response => {
                    this.products = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        selectArticle(article) {
            this.products = [];
            this.article_id = article.id;
            this.article = article.articulo;

            if (article.stock.length > 0) {
                this.stock = article.stock[0].total * 1;
            } else {
                this.stock = 0;
            }
            console.log(this.article_id);
        },

        traer: async function() {
            if (this.datos.includes("vendedores")) {
                let response = await this.index({
                    url: "api/estadisticas/xvendedor",
                    vendedores: this.user
                });
                console.log(response);
            } else if (this.datos.includes("fecha")) {
                let response = await this.index({
                    url: "api/estadisticas/xfecha",
                    from: this.desde,
                    to: this.hasta
                });
                console.log(response);
            } else if (this.datos.includes("producto")) {
                let response = await this.index({
                    url: "api/estadisticas/xarticulo",
                    articulo: this.article_id
                });
                console.log(response);
            }
        }
    }
};
</script>

<style>
</style>
