<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs11 sm5>
                <!-- Input Productos -->

                <v-layout justify-space-around wrap>
                    <v-flex xs11>
                        <!-- Input Buscar Articulos -->
                        <v-form ref="formFindArticle">
                            <v-text-field
                                @keyup="findArticle()"
                                autofocus
                                v-model="article"
                                label="Articulo"
                                box
                                single-line
                            ></v-text-field>
                        </v-form>

                        <!-- Tabla Buscar Articulos -->
                        <transition>
                            <v-data-table
                                v-show="article != null && article != '' && products.length > 0"
                                no-data-text="El producto no se encuentra en la base de datos."
                                hide-actions
                                hide-headers
                                :items="products"
                                class="search-table"
                            >
                                <template v-slot:items="article">
                                    <tr
                                        @click="selectArticle(article.item)"
                                        style="cursor: pointer;"
                                    >
                                        <td>{{ article.item.codarticulo }}</td>
                                        <td>{{ article.item.articulo }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </transition>
                    </v-flex>
                </v-layout>
            </v-flex>

            <v-flex xs11 sm5>
                <!-- Input Clientes -->
                <v-select
                    :items="movimiento"
                    v-model="tmov"
                    item-text="movimiento"
                    item-value="movimiento"
                    label="Outline style"
                    outline
                    multiple
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex xs11>
                <v-range-selector :start-date.sync="range.start" :end-date.sync="range.end" />
            </v-flex>
        </v-layout>
        <br />
        <v-layout justify-center>
            <v-btn @click="getReports()" color="primary">Filtrar</v-btn>
        </v-layout>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapState } from "vuex";
import VRangeSelector from "vuelendar/components/vl-range-selector";
export default {
    name: "ReporteInventarios",
    components: {
        VRangeSelector
    },
    data() {
        return {
            range: {},
            producto: null,
            tmov: null,
            productoSelected: null,
            article: null,
            products: [],
            article_id: null,
            buscarArticulo: null,
            movimiento: [
                 {
                    movimiento: "ALTA",
                    valor: 0
                },
                {
                    movimiento: "VENTA",
                    valor: 1
                },
                { movimiento: "COMPRA", valor: 2 },
                { movimiento: "DEVOLUCION", valor: 3 },
                { movimiento: "VENCIMIENTO", valor: 4 }
            ],
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },
    computed: {
        ...mapState("crudx", ["form"])
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),
        getReports() {
            let data = {
                producto: this.article_id,
                fechas: [this.range.start, this.range.end],
                movimiento: this.tmov
            };
            axios.post("api/estadisticas/inventarios", data).then(response => {
                console.log(response.data);
            });
        },
        findArticle: async function() {
            let response = await this.index({
                url: "/api/articulos",
                buscarArticulo: this.article,
                limit: 5
            });

            this.products = response.articulos;
        },

        //Seleccionar Articulo
        selectArticle(article) {
            this.products = [];
            this.article_id = article.id;
            this.article = article.articulo;
        }
    }
};
</script>

<style>
</style>
