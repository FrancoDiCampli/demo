<template>
    <div>
        <!-- Formulario -->
        <v-form ref="formDetalles">
            <v-layout justify-space-around wrap>
                <v-flex xs11>
                    <!-- Input Buscar Articulos -->
                    <v-text-field
                        @keyup="findArticle()"
                        autofocus
                        v-model="article"
                        :rules="[rules.required]"
                        label="Articulo"
                        box
                        single-line
                    ></v-text-field>

                    <!-- Tabla Buscar Articulos -->
                    <transition name="fade">
                        <v-data-table
                            v-show="article != null && article != '' && products.length > 0"
                            no-data-text="El cliente no se encuentra en la base de datos."
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
                                    <td>{{ article.item.precio }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </transition>
                </v-flex>

                <!-- Input Cantidad -->
                <v-flex xs11 sm3 mx-1>
                    <v-text-field
                        v-model="quantity"
                        :disabled="article == null || article == '' ? true : false"
                        :rules="[rules.required, rules.maxStock]"
                        @keyup.enter="fillDetails()"
                        label="Cantidad"
                        hint="Cantidad"
                        box
                        single-line
                    ></v-text-field>
                </v-flex>

                <!-- Input Precio -->
                <v-flex xs11 sm3 mx-1>
                    <v-text-field
                        v-model="price"
                        :rules="[rules.required]"
                        label="Precio"
                        disabled
                        box
                        single-line
                    ></v-text-field>
                </v-flex>

                <!-- Input Subtotal -->
                <v-flex xs11 sm3 mx-1>
                    <v-text-field
                        v-model="subtotal"
                        :rules="[rules.required]"
                        label="Subtotal"
                        disabled
                        box
                        single-line
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-form>
        <!-- Tabla Detalles -->
        <v-layout justify-space-around>
            <v-flex xs11>
                <v-data-table :headers="detailsHeader" :items="details" hide-actions>
                    <template v-slot:items="detail">
                        <td>{{ detail.item.articulo }}</td>
                        <td>
                            <v-edit-dialog :return-value.sync="detail.item.cantidad" lazy>
                                {{ detail.item.cantidad }}
                                <template v-slot:input>
                                    <v-text-field
                                        v-model="detail.item.cantidad"
                                        label="Cantidad"
                                        single-line
                                        @keyup="updateDetails()"
                                    ></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td>{{ detail.item.precio }}</td>
                        <td>{{ detail.item.subtotal }}</td>
                        <td>
                            <v-btn @click="removeDetail(detail.item)" flat icon color="primary">
                                <v-icon size="medium">fas fa-times</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations } from "vuex";

export default {
    name: "FacturasArticulo",

    data() {
        return {
            article: null,
            article_id: null,
            quantity: null,
            price: null,
            stock: 0,
            products: [],
            articleSelected: null,
            details: [],
            detailsHeader: [
                { text: "Articulo", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Subtotal", sortable: false },
                { text: "", sortable: false }
            ],
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                maxStock: value =>
                    value * 1 <= this.stock || "Stock Insuficiente"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"]),

        subtotal: {
            set() {},

            get() {
                if (
                    this.quantity != null &&
                    this.quantity != "" &&
                    this.price != null &&
                    this.price != ""
                ) {
                    return this.price * this.quantity;
                } else {
                    return null;
                }
            }
        }
    },

    updated() {
        if (this.details.length > 0) {
            let sub = 0;
            for (let i = 0; i < this.details.length; i++) {
                sub += this.details[i].subtotal * 1;
            }
            this.FillSubtotal({ sub: sub });
        }
    },

    methods: {
        ...mapMutations(["FillSubtotal"]),

        //Buscar Articulo
        findArticle() {
            this.$refs.formDetalles.resetValidation();
            this.articleSelected = null;
            this.quantity = null;
            this.price = null;
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

        //Seleccionar Articulo
        selectArticle(article) {
            this.products = [];
            this.article_id = article.id;
            this.article = article.articulo;
            this.price = article.precio;
            this.stock = article.stock[0].total * 1;
        },

        //LLenar Array de Detalles
        fillDetails() {
            if (this.$refs.formDetalles.validate()) {
                let detail = {
                    articulo_id: this.article_id,
                    articulo: this.article,
                    cantidad: this.quantity,
                    precio: this.price,
                    subtotal: this.subtotal
                };

                this.details.push(detail);
                this.form.detalle = this.details;

                this.$refs.formDetalles.reset();
            }
        },

        //Borrar un Detalle del Array
        removeDetail(detail) {
            let index = this.details.indexOf(detail);
            this.details.splice(index, 1);

            if (this.details.length < 1) {
                this.$store.state.subtotal == null;
            } else {
                this.form.detalle = this.details;
            }
        },

        //Actualizar cantidad y subtotal de un detalle
        updateDetails() {
            this.details.forEach(function(detail) {
                detail.subtotal = detail.quantity * detail.price;
            });

            this.form.detalle = this.details;
        }
    }
};
</script>