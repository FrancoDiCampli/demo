<template>
    <div>
        <v-form ref="formFactura" @submit.prevent="saveFactura">
            <!-- Facturas Headers -->
            <div>
                <v-card-title>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm5 mx-1>
                            <h1 class="text-xs-center text-sm-left">Nuevo Remito</h1>
                        </v-flex>
                        <v-flex xs12 sm5 mx-1 class="data text-xs-center text-sm-right">
                            <p>
                                <b>Punto de Venta:</b> 0003
                            </p>
                            <p>
                                <b>Comprobante Nº:</b> 2
                            </p>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <br />

                <!-- Snackbar -->
                <v-snackbar color="primary" v-model="snackbar" :timeout="6000" right top>
                    {{ snackbarText }} GUARDADO
                    <v-btn color="white" flat @click="snackbar = false" icon>
                        <v-icon>fas fa-times</v-icon>
                    </v-btn>
                </v-snackbar>
            </div>
            <!-- End Snack -->

            <!-- Proveedores -->
            <div>
                <!-- Formulario -->
                <v-layout justify-space-around wrap>
                    <v-flex xs11 sm5>
                        <!-- Input Clientes -->
                        <v-form ref="formFindClient">
                            <v-text-field
                                @keyup="findClient()"
                                v-model="client"
                                label="Cliente"
                                box
                                single-line
                            ></v-text-field>
                        </v-form>

                        <!-- Tabla Clientes -->
                        <transition name="expand">
                            <v-data-table
                                v-show="client != null && client != '' && customers.length > 0"
                                no-data-text="El cliente no se encuentra en la base de datos."
                                hide-actions
                                hide-headers
                                :items="customers"
                                class="search-table"
                            >
                                <template v-slot:items="client">
                                    <tr @click="selectClient(client.item)" style="cursor: pointer;">
                                        <td>{{ client.item.cuit }}</td>
                                        <td>{{ client.item.razonsocial }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </transition>
                    </v-flex>

                    <!-- Input Condición de Venta -->
                </v-layout>
                <!-- Detalles Clientes -->
                <v-layout v-if="detailClient.id" justify-space-around>
                    <v-flex xs11>
                        <template>
                            <v-expansion-panel class="elevation-0 expansion-border">
                                <v-expansion-panel-content>
                                    <template v-slot:header>
                                        <div>Más Detalles</div>
                                    </template>
                                    <v-card-text>
                                        <p>
                                            <b>CUIT:</b>
                                            {{detailClient.cliente.cuit}}
                                        </p>
                                        <p>
                                            <b>Razón Social:</b>
                                            {{detailClient.cliente.razonsocial}}
                                        </p>
                                        <p>
                                            <b>Condición Frente al IVA:</b>
                                            Que te importa
                                        </p>
                                        <p>
                                            <b>Domicilio:</b>
                                            {{detailClient.cliente.direccion}}
                                        </p>
                                    </v-card-text>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </template>
                        <br />
                    </v-flex>
                </v-layout>
            </div>
            <!-- EndProveedores -->
        </v-form>
    </div>
</template>

<script>
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    data() {
        return {
            //Data Clientes
            client: "CONSUMIDOR FINAL",
            detailClient: [],
            customers: [],

            //Data Articulos
            article: null,
            article_id: null,
            quantity: null,
            price: null,
            stock: 0,
            products: [],
            details: [],
            detailsHeader: [
                { text: "Articulo", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Precio", sortable: false, class: "hidden-xs-only" },
                { text: "Subtotal", sortable: false },
                { text: "", sortable: false }
            ],

            //Data Resumen
            types: ["REMITO X", "FACTURA C"],
            tipo: "REMITO X",

            //Data General
            snackbar: false,
            snackbarText: "",
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                maxStock: value =>
                    value * 1 <= this.stock || "Stock Insuficiente"
            }
        };
    },
    computed: {
        ...mapState("crudx", ["form"]),

        //Computed Articulos
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
        },

        //Computed Resumen
        subtotalFactura: {
            set() {},

            get() {
                if (this.details.length > 0) {
                    let sub = 0;
                    for (let i = 0; i < this.details.length; i++) {
                        sub += this.details[i].subtotal * 1;
                    }
                    this.form.subtotal = sub;
                    return sub;
                } else {
                    this.form.subtotal = null;
                    return null;
                }
            }
        },

        total: {
            set() {},
            get() {
                if (this.subtotalFactura != null) {
                    let boni = 0;
                    let reca = 0;

                    if (this.form.bonificacion) {
                        if (this.form.bonificacion.length > 0) {
                            boni =
                                (this.form.bonificacion *
                                    this.subtotalFactura) /
                                100;
                        }
                    }

                    if (this.form.recargo) {
                        if (this.form.recargo.length > 0) {
                            reca =
                                (this.form.recargo * this.subtotalFactura) /
                                100;
                        }
                    }
                    this.form.total = this.subtotalFactura - boni + reca;
                    return this.subtotalFactura - boni + reca;
                } else {
                    this.form.total = null;
                    return null;
                }
            }
        }
    },

    mounted() {
        //Mounted Clientes
        this.form.cliente_id = 1;
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        //Metodos Clientes

        // Buscar los clientes
        findClient: async function() {
            this.detailClient = [];

            if (this.client == "0") {
                this.customers = [];
                this.detailClient = [];

                // this.form.cliente_id = 1;
            } else if (this.$refs.formFindClient.validate()) {
                let response = await this.index({
                    url: "/api/suppliers",
                    buscarCliente: this.client,
                    limit: 5
                });

                this.customers = response;
            }
        },

        // Seleccionar un Cliente
        selectClient(client) {
            this.customers = [];
            this.detailClient = [];
            this.client = client.razonsocial;
            this.form.cliente_id = client.id;

            axios
                .get("/api/suppliers/" + client.id)
                .then(response => {
                    this.detailClient = response.data;
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        //Metodos Articulos

        //Buscar Articulo
        findArticle: async function() {
            this.$refs.formDetalles.resetValidation();
            this.quantity = null;
            this.price = null;
            if (this.$refs.formFindArticle.validate()) {
                let response = await this.index({
                    url: "/api/articulos",
                    buscarArticulo: this.article,
                    limit: 5
                });

                this.products = response.articulos;
            }
        },

        //Seleccionar Articulo
        selectArticle(article) {
            this.products = [];
            this.article_id = article.id;
            this.article = article.articulo;
            this.price = article.precio;
            if (article.stock.length > 0) {
                this.stock = article.stock[0].total * 1;
            } else {
                this.stock = 0;
            }
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
                this.stock = 0;
            }
        },

        //Borrar un Detalle del Array
        removeDetail(detail) {
            let index = this.details.indexOf(detail);
            this.details.splice(index, 1);

            this.form.detalle = this.details;
        },

        //Actualizar cantidad y subtotal de un detalle
        updateDetails() {
            this.details.forEach(function(detail) {
                if (detail.cantidad.length > 0) {
                    detail.subtotal = detail.cantidad * detail.precio;
                } else {
                    detail.subtotal = 0;
                }
            });

            this.form.detalle = this.details;
        },

        //Metodos Generales

        //Guardar Factura
        saveFactura: async function() {
            //Establecer Campos no establecidos
            this.form.condicion = this.condicion;
            this.form.tipo = this.tipo;

            //Establecer Mensaje del Snackbar
            this.snackbarText = this.tipo;
            if (this.$refs.formFactura.validate()) {
                //Guardar Factura
                await this.save({ url: "/api/facturas" });
                //Activar Snackbar
                this.snackbar = true;
                //Reset Formularios
                this.details = [];
                await this.$refs.formDetalles.reset();
                await this.$refs.formFactura.reset();
                //Establecer Valores Predeterminados
                this.form.cliente_id = 1;
                this.client = "CONSUMIDOR FINAL";
                this.condicion = "CONTADO";
                this.tipo = "REMITO X";
            }
        },

        //Resetear Factura
        cancelFactura: async function() {
            //Reset Formularios
            this.details = [];
            await this.$refs.formFindClient.reset();
            await this.$refs.formFindArticle.reset();
            await this.$refs.formDetalles.reset();
            await this.$refs.formFactura.reset();
            //Establecer Valores Predeterminados
            this.form.cliente_id = 1;
            this.client = "CONSUMIDOR FINAL";
            this.condicion = "CONTADO";
            this.tipo = "REMITO X";
        }
    }
};
</script>

<style>
</style>
