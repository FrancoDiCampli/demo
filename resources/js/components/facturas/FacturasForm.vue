<template>
    <div>
        <v-form ref="formFactura" @submit.prevent="saveFactura">
            <!-- Facturas Headers -->
            <div>
                <v-card-title>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm5 mx-1>
                            <h1 class="text-xs-center text-sm-left">Nueva Factura</h1>
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
                <br>

                <!-- Snackbar -->
                <v-snackbar color="primary" v-model="snackbar" :timeout="6000" right top>
                    {{ snackbarText }} GUARDADO
                    <v-btn color="white" flat @click="snackbar = false" icon>
                        <v-icon>fas fa-times</v-icon>
                    </v-btn>
                </v-snackbar>
            </div>
            <!---------------------->
            <!-- Facturas Clientes -->
            <div>
                <!-- Formulario -->
                <v-layout justify-space-around wrap>
                    <v-flex xs11 sm5>
                        <!-- Input Clientes -->
                        <v-text-field
                            @keyup="findClient()"
                            v-model="client"
                            :rules="[rules.required]"
                            label="Cliente"
                            box
                            single-line
                        ></v-text-field>

                        <!-- Tabla Clientes -->
                        <transition name="fade">
                            <v-data-table
                                v-show="client != null && client != '' && customers.length > 0"
                                no-data-text="El cliente no se encuentra en la base de datos."
                                hide-actions
                                hide-headers
                                :items="customers"
                                class="search-table"
                            >
                                <template v-slot:items="client">
                                    <tr
                                        @click="clientSelected = client.item.id"
                                        @dblclick="selectClient(client.item)"
                                        style="cursor: pointer;"
                                        :style="clientSelected == client.item.id ? 'background-color: #26A69A; color: #FFFFFF;' : ''"
                                    >
                                        <td>{{ client.item.documentounico }}</td>
                                        <td>{{ client.item.razonsocial }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </transition>
                    </v-flex>

                    <!-- Input Condición de Venta -->
                    <v-flex xs11 sm5>
                        <v-select
                            v-model="condicion"
                            :items="terms"
                            :rules="[rules.required]"
                            label="Condición"
                            hint="Condición"
                            box
                            single-line
                        ></v-select>
                    </v-flex>
                </v-layout>
                <!-- Detalles Clientes -->
                <v-layout v-if="detailClient.cliente" justify-space-around>
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
                                            {{detailClient.cliente.documentounico}}
                                        </p>
                                        <p>
                                            <b>Razón Social:</b>
                                            {{detailClient.cliente.razonsocial}}
                                        </p>
                                        <p>
                                            <b>Condición Frente al IVA:</b>
                                            {{detailClient.cliente.condicioniva}}
                                        </p>
                                        <p>
                                            <b>Domicilio:</b>
                                            {{detailClient.cliente.direccion}}
                                        </p>
                                    </v-card-text>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </template>
                        <br>
                    </v-flex>
                </v-layout>
            </div>
            <!---------------------->
            <!-- Facturas Arituclos -->
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
                                    <v-btn
                                        @click="removeDetail(detail.item)"
                                        flat
                                        icon
                                        color="primary"
                                    >
                                        <v-icon size="medium">fas fa-times</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </div>
            <!---------------------->
            <!-- Facturas Resumen -->
            <br>
            <div>
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm5 mx-1>
                        <v-layout justify-space-around wrap>
                            <v-flex xs11>
                                <v-text-field
                                    v-model="form.bonificacion"
                                    label="Bonificacion"
                                    hint="Bonificacion"
                                    box
                                    single-line
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs11>
                                <v-text-field
                                    v-model="form.recargo"
                                    label="Recargo"
                                    hint="Recargo"
                                    box
                                    single-line
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs11>
                                <v-select
                                    v-model="tipo"
                                    :items="types"
                                    :rules="[rules.required]"
                                    label="Tipo Comprobante"
                                    hint="Tipo Comprobante"
                                    box
                                    single-line
                                ></v-select>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 sm5 mx-1>
                        <v-layout justify-space-around wrap>
                            <v-flex xs11>
                                <v-text-field
                                    v-model="subtotalFactura"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Subtotal"
                                    hint="Subtotal"
                                    box
                                    single-line
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs11>
                                <v-text-field
                                    v-model="total"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Total"
                                    hint="Total"
                                    box
                                    single-line
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs11>
                                <v-layout justify-center>
                                    <v-btn @click="cancelFactura()" outline color="primary">Cancelar</v-btn>
                                    <v-btn type="submit" color="primary">Guardar</v-btn>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </div>
            <!---------------------->
        </v-form>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "FacturasUnique",

    data() {
        return {
            //Data Clientes
            client: "CONSUMIDOR FINAL",
            detailClient: [],
            customers: [],
            clientSelected: null,
            terms: ["CONTADO", "CREDITO / DEBITO", "CUENTA CORRIENTE"],
            condicion: "CONTADO",

            //Data Articulos
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
        ...mapActions("crudx", ["save"]),

        //Metodos Clientes

        // Buscar los clientes
        findClient() {
            this.clientSelected = null;
            this.detailClient = [];
            if (this.client == "0") {
                this.customers = [];
                this.detailClient = [];
                this.client = "CONSUMIDOR FINAL";
                this.form.cliente_id = 0;
            } else {
                axios
                    .get("/api/clientes", {
                        params: {
                            buscarCliente: this.client,
                            limit: 5
                        }
                    })
                    .then(response => {
                        this.customers = response.data.clientes;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        // Seleccionar un Cliente
        selectClient(client) {
            this.customers = [];
            this.detailClient = [];
            this.client = client.razonsocial;
            this.form.cliente_id = client.id;

            axios
                .get("/api/clientes/" + client.id)
                .then(response => {
                    this.detailClient = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        //Metodos Articulos

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
.data {
    font-size: 12px;
    line-height: 5px;
    margin-top: 8px;
}

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

.expansion-border {
    border-bottom: 1px solid #aaaaaa;
}
</style>
