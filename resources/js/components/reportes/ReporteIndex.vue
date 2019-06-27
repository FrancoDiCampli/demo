<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs11 sm5>
                <v-select
                    v-model="vendedor"
                    hint="vendedor"
                    :items="sellers"
                    item-text="name"
                    item-value="id"
                    label="Vendedor"
                    box
                    single-line
                ></v-select>
            </v-flex>
            <v-flex xs11 sm5>
                <!-- Input Clientes -->
                <v-text-field
                    v-model="producto"
                    @keyup="getArticles()"
                    label="Producto"
                    box
                    single-line
                ></v-text-field>

                <!-- Tabla Clientes -->
                <v-data-table
                    v-show="productoSelected == null && producto != null && producto != ''"
                    no-data-text="El Producto no se encuentra en la base de datos."
                    hide-actions
                    hide-headers
                    :items="articles"
                    class="search-table"
                >
                    <template v-slot:items="article">
                        <tr
                            style="cursor: pointer;"
                            @click="productoSelected = article.item.id; producto = article.item.articulo;"
                        >
                            <td>{{ article.item.codarticulo }}</td>
                            <td>{{ article.item.articulo }}</td>
                        </tr>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
        <v-layout justify-space-around>
            <v-flex xs11 sm5>
                <v-select
                    v-model="condicionventa"
                    hint="condicion"
                    :items="terms"
                    label="Condicion"
                    box
                    single-line
                    multiple
                ></v-select>
            </v-flex>
            <v-flex xs11 sm5>
                <v-select
                    v-model="client"
                    hint="Clientes"
                    :items="clients"
                    item-text="nombre"
                    item-value="id"
                    label="clientes"
                    box
                    single-line
                    multiple
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout justify-space-around>
            <v-flex xs11>
                <v-range-selector :start-date.sync="range.start" :end-date.sync="range.end"/>
            </v-flex>
        </v-layout>
        <br>
        <v-layout justify-center>
            <v-btn @click="getReports()" color="primary">Filtrar</v-btn>
        </v-layout>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
import VRangeSelector from "vuelendar/components/vl-range-selector";
export default {
    name: "ReporteIndex",

    components: {
        VRangeSelector
    },

    data() {
        return {
            condicionventa: [],
            vendedor: [],
            sellers: [],
            producto: null,
            productoSelected: null,
            articles: [],
            range: {},
            reports: [],
            terms: ["CONTADO", "CREDITO / DEBITO", "CUENTA CORRIENTE"],
            clients: [
                {
                    id: 2,
                    nombre: "Franco"
                },
                {
                    id: 4,
                    nombre: "Juan"
                },
                {
                    id: 5,
                    nombre: "Maria"
                },
                {
                    id: 6,
                    nombre: "Maria"
                }
            ],
            client: []
        };
    },

    mounted() {
        this.getSellers();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        getSellers: async function() {
            let response = await this.index({ url: "api/users/index" });
            this.sellers = response;
        },
        getClients: async function() {
            let response = await this.index({ url: "api/clientes/index" });
            this.clients = response;
            console.log(response);
        },

        getArticles: async function() {
            this.productoSelected = null;
            axios
                .get("/api/articulos", {
                    params: {
                        buscarArticulo: this.producto,
                        limit: 5
                    }
                })
                .then(response => {
                    this.articles = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.articles = [];
                });
        },

        getReports() {
            let data = {
                vendedor: this.vendedor,
                producto: this.producto,
                fechas: [this.range.start, this.range.end],
                condicion: this.condicionventa,
                clientes: this.client
            };
            axios.post("api/estadisticas/reportes", data).then(response => {
                console.log(response.data);
            });

            // let response = await this.index({
            //     url: "api/estadisticas/reportes",
            //     data: {
            //         vendedor: this.vendedor,
            //         producto: this.producto,
            //         fechaDesde: this.range.start,
            //         fechaHasta: this.range.end
            //     }
            // });

            // axios
            //     .post("api/estadisticas/reportes", {
            //         params: {
            //             vendedor: this.vendedor,
            //             producto: this.producto,
            //             fechaDesde: this.range.start,
            //             fechaHasta: this.range.end
            //         }
            //     })
            //     .then(response => {
            //         console.log(response.data);
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     });
        }
    }
};
</script>

<style>
</style>
