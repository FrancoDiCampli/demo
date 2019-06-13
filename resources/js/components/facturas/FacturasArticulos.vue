<template>
    <div>
        <v-layout justify-space-around wrap>
            <v-flex xs11>
                <v-text-field
                    @keyup="findArticle()"
                    v-model="article"
                    label="Articulo"
                    box
                    single-line
                ></v-text-field>
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
                                @click="selected = article.item.id"
                                @dblclick="selectArticle(article.item)"
                                style="cursor: pointer;"
                                :style="selected == article.item.id ? 'background-color: #26A69A; color: #FFFFFF;' : ''"
                            >
                                <td>{{ article.item.codarticulo }}</td>
                                <td>{{ article.item.articulo }}</td>
                                <td>{{ article.item.precio }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>
            <v-flex xs12 sm3 mx-1>
                <v-text-field
                    v-model="quantity"
                    @keyup.enter="fillDetails()"
                    label="Cantidad"
                    hint="Cantidad"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm3 mx-1>
                <v-text-field v-model="price" label="Precio" disabled box single-line></v-text-field>
            </v-flex>
            <v-flex xs12 sm3 mx-1>
                <v-text-field v-model="subtotal" label="Subtotal" disabled box single-line></v-text-field>
            </v-flex>
        </v-layout>
        <v-layout justify-space-around>
            <v-flex xs11>
                <v-data-table :headers="detailsHeader" :items="details" hide-actions>
                    <template v-slot:items="detail">
                        <td>{{ detail.item.article }}</td>
                        <td>
                            <v-edit-dialog :return-value.sync="detail.item.quantity" lazy>
                                {{ detail.item.quantity }}
                                <template v-slot:input>
                                    <v-text-field
                                        v-model="detail.item.quantity"
                                        label="Cantidad"
                                        single-line
                                        @keyup="updateDetails()"
                                    ></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td>{{ detail.item.price }}</td>
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
        <v-layout justify-space-around>
            <v-flex xs12 sm5 mx-1>
                <v-layout wrap>
                    <v-flex xs12>
                        <v-text-field
                            v-model="form.bonificacion"
                            label="Bonificacion"
                            hint="Bonificacion"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            v-model="form.recargo"
                            label="Recargo"
                            hint="Recargo"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-select
                            v-model="form.tipo"
                            :items="types"
                            label="Tipo Comprobante"
                            hint="Tipo Comprobante"
                            box
                            single-line
                        ></v-select>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs12 sm5 mx-1>
                <v-layout wrap>
                    <v-flex xs12>
                        <v-text-field
                            v-model="subtotalFactura"
                            disabled
                            label="Subtotal"
                            hint="Subtotal"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            v-model="total"
                            disabled
                            label="Total"
                            hint="Total"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>


<script>
//Axios
import axios from "axios";

//Vuex
import { mapState } from "vuex";

export default {
    name: "FacturasClientes",

    data() {
        return {
            article: null,
            article_id: null,
            quantity: null,
            price: null,
            products: [],
            selected: null,
            details: [],
            detailsHeader: [
                { text: "Articulo", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Subtotal", sortable: false },
                { text: "", sortable: false }
            ],
            types: ["Remito X", "Factura C"]
        };
    },

    computed: {
        ...mapState("crudx", ["form"]),

        subtotal() {
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
        },

        subtotalFactura() {
            if (this.details.length > 0) {
                let sub = 0;
                for (let i = 0; i < this.details.length; i++) {
                    sub += this.details[i].subtotal * 1;
                }

                this.form.subtotal = sub;

                return sub;
            } else {
                return null;
            }
        },

        total() {
            if (this.details.length > 0) {
                let sub = 0;
                for (let i = 0; i < this.details.length; i++) {
                    sub += this.details[i].subtotal * 1;
                }

                let boni = 0;
                let reca = 0;

                if (this.form.bonificacion) {
                    boni = (this.form.bonificacion * sub) / 100;
                }

                if (this.form.recargo) {
                    reca = (this.form.recargo * sub) / 100;
                }

                sub = sub - boni + reca;

                this.form.total = sub;

                return sub;
            } else {
                return null;
            }
        }
    },

    methods: {
        findArticle() {
            this.selected = null;
            this.quantity = null;
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
            this.price = article.precio;
        },

        fillDetails() {
            if (
                this.article != null &&
                this.article != "" &&
                this.subtotal != null &&
                this.subtotal != ""
            ) {
                let detail = {
                    articulo_id: this.article_id,
                    article: this.article,
                    quantity: this.quantity,
                    price: this.price,
                    subtotal: this.subtotal
                };
                this.details.push(detail);

                this.form.detalle = this.details;
            }
        },

        removeDetail(detail) {
            let index = this.details.indexOf(detail);
            this.details.splice(index, 1);

            this.form.detalle = this.details;
        },

        updateDetails() {
            this.details.forEach(function(detail) {
                detail.subtotal = detail.quantity * detail.price;
            });

            this.form.detalle = this.details;
        }
    }
};
</script>

<style>
.search-table {
    border: solid 2px #26a69a;
    margin-top: -30px;
    border-top: none;
    margin-bottom: 20px;
    border-radius: 0px 0px 5px 5px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter {
    transform: translateY(-60px);
}

.fade-leave-to {
    opacity: 0;
}
</style>
