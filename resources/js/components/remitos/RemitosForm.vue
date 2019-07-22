<template>
    <div>
        <v-form ref="formRemito" @submit.prevent="saveFactura">
            <!-- Compras Headers -->
            <div>
                <v-card-title>
                    <v-layout justify-space-between wrap>
                        <v-flex xs12 sm5 mx-1>
                            <h2 class="text-xs-center text-sm-left">Nueva Compra</h2>
                        </v-flex>
                        <v-flex xs12 sm5 mx-1 class="dataPresupuesto text-xs-center text-sm-right">
                            <p>
                                <b>Compra Nº:</b>
                            </p>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <br />
            </div>
            <!---------------------->
            <!-- Compras Proveedor -->
            <div>
                <!-- Formulario -->
                <v-layout justify-space-around wrap>
                    <v-flex xs12 px-3>
                        <!-- Input Proveedor -->
                        <v-text-field
                            @keyup="findSupplier()"
                            v-model="form.supplier"
                            label="Proveedor"
                            box
                        ></v-text-field>
                        <!-- Tabla Proveedores -->
                        <transition name="expand">
                            <v-data-table
                                v-show="form.supplier && suppliers.length > 0"
                                no-data-text="El Proveedores no se encuentra en la base de datos."
                                hide-actions
                                hide-headers
                                :items="suppliers"
                                class="search-table"
                            >
                                <template v-slot:items="supplier">
                                    <tr
                                        @click="selectSupplier(supplier.item)"
                                        style="cursor: pointer;"
                                    >
                                        <td>{{ supplier.item.cuit }}</td>
                                        <td>{{ supplier.item.razonsocial }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </transition>
                    </v-flex>
                </v-layout>
                <!-- Detalles Proveedor -->
                <v-layout v-if="detailSupplier.supplier" justify-space-around>
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
                                            {{detailSupplier.supplier.cuit}}
                                        </p>
                                        <p>
                                            <b>Razón Social:</b>
                                            {{detailSupplier.supplier.razonsocial}}
                                        </p>
                                        <p>
                                            <b>Domicilio:</b>
                                            {{detailSupplier.supplier.direccion}}
                                        </p>
                                    </v-card-text>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </template>
                        <br />
                    </v-flex>
                </v-layout>
            </div>
            <!---------------------->
            <!-- Compras Articulo -->
            <div>
                <!-- Formulario -->
                <v-form ref="formDetalles">
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 px-3>
                            <!-- Input Buscar Productos -->
                            <v-text-field
                                @keyup="findProducto()"
                                autofocus
                                v-model="form.producto"
                                label="Producto"
                                box
                            ></v-text-field>

                            <!-- Tabla Buscar Productos -->
                            <transition>
                                <v-data-table
                                    v-show="form.producto && productos.length > 0"
                                    no-data-text="El producto no se encuentra en la base de datos."
                                    hide-actions
                                    :headers="productosHeaders"
                                    :items="productos"
                                    class="search-table"
                                >
                                    <template v-slot:items="producto">
                                        <tr
                                            @click="selectProducto(producto.item)"
                                            :style="
                                            producto.item.stock ? 
                                            'cursor: pointer;' : 
                                            ''"
                                        >
                                            <td
                                                class="hidden-xs-only"
                                            >{{ producto.item.codarticulo }}</td>
                                            <td>{{ producto.item.articulo }}</td>
                                            <td>{{ producto.item.precio }}</td>
                                            <td>
                                                <div v-if="producto.item.stock <= 0">0</div>
                                                <div v-else>{{ producto.item.stock }}</div>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </transition>
                        </v-flex>
                    </v-layout>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm6 px-3>
                            <v-text-field
                                v-model="form.lote"
                                @keyup="findLote()"
                                label="Lote"
                                box
                                :rules="[rules.required]"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 px-3>
                            <v-text-field
                                v-model="form.cantidad"
                                label="Cantidad"
                                box
                                :rules="
                                movimiento == 'ALTA' || movimiento == 'INCREMENTO' || movimiento == 'MODIFICACION' ? 
                                [rules.required] : 
                                [rules.required, rules.cantidadMaxima]
                            "
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 px-3>
                            <v-select
                                v-model="movimiento"
                                :disabled="disabledMovimiento"
                                :items="movimientos"
                                :rules="[rules.required]"
                                label="Movimiento"
                                box
                            ></v-select>
                        </v-flex>

                        <v-flex xs12 sm6 px-3>
                            <v-dialog
                                ref="vencimiento"
                                v-model="modalVencimiento"
                                :return-value.sync="form.vencimiento"
                                persistent
                                lazy
                                full-width
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="form.vencimiento"
                                        label="Fecha de Vencimiento"
                                        :rules="[rules.required]"
                                        box
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="form.vencimiento"
                                    scrollable
                                    locale="es"
                                    format="DD/MM/YYYY"
                                    color="primary"
                                >
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        flat
                                        color="primary"
                                        @click="modalVencimiento = false"
                                    >Cancel</v-btn>
                                    <v-btn
                                        flat
                                        color="primary"
                                        @click="$refs.vencimiento.save(form.vencimiento)"
                                    >OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>
                        <v-flex xs12 sm6 lg3 px-3>
                            <v-text-field
                                v-model="form.costo"
                                :rules="[rules.required]"
                                label="Costo"
                                box
                                type="number"
                                class="input-number"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 lg3 px-3>
                            <v-text-field
                                v-model="form.utilidades"
                                :rules="[rules.required]"
                                label="Utilidades %"
                                box
                                type="number"
                                class="input-number"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 lg3 px-3>
                            <v-select
                                v-model="alicuota"
                                :items="alicuotas"
                                :rules="[rules.required]"
                                label="Alicuota %"
                                box
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 sm6 lg3 px-3>
                            <v-text-field
                                v-model="precio"
                                :rules="[rules.required]"
                                label="Precio"
                                box
                                type="number"
                                class="input-number"
                                disabled
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-form>

                <!-- Tabla Detalles -->
                <v-layout justify-space-around>
                    <v-flex xs11>
                        <v-data-table :headers="detallesHeader" :items="detalles" hide-actions>
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
                                <td>
                                    <v-edit-dialog :return-value.sync="detail.item.lote" lazy>
                                        {{ detail.item.lote }}
                                        <template v-slot:input>
                                            <v-text-field
                                                v-model="detail.item.lote"
                                                label="Lote"
                                                single-line
                                                @keyup="updateDetails()"
                                            ></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td>{{ detail.item.vence }}</td>
                                <td>
                                    <v-edit-dialog :return-value.sync="detail.item.precio" lazy>
                                        {{ detail.item.precio }}
                                        <template v-slot:input>
                                            <v-text-field
                                                v-model="detail.item.precio"
                                                label="Precio"
                                                single-line
                                                @keyup="updateDetails()"
                                            ></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
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
            <!-- Compras Resumen -->
            <br />
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
                            <v-flex xs11></v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
                <v-layout justify-center>
                    <v-btn @click="cancelFactura()" outline color="primary">Cancelar</v-btn>
                    <v-btn type="submit" color="primary">Guardar</v-btn>
                </v-layout>
                <br />
            </div>
            <!---------------------->
        </v-form>
    </div>
</template>

<script>
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "RemitosForm",

    data() {
        return {
            //_________________________Data Headers_________________________//
            numFactura: null,

            //_________________________Data Proveedor_________________________//
            suppliers: [],
            detailSupplier: [],

            //_________________________Data Articulos_________________________//
            modalVencimiento: false,
            alicuota: null,
            alicuotas: [21, 10.5],
            suppliers: [],
            active: false,
            movimiento: "ALTA",
            movimientos: [
                "ALTA",
                "INCREMENTO",
                "DEVOLUCION",
                "VENCIMIENTO",
                "DECREMENTO",
                "INCREMENTO"
            ],
            disabledMovimiento: true,
            process: false,
            preventSaveDialog: false,
            msg: null,
            cantidadMaxima: 999999999,
            productos: [],
            detalles: [],
            productosHeaders: [
                { text: "Codigo", sortable: false, class: "hidden-xs-only" },
                { text: "Articulo", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Stock", sortable: false }
            ],
            detallesHeader: [
                { text: "Articulo", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Precio", sortable: false, class: "hidden-xs-only" },
                { text: "Subtotal", sortable: false },
                { text: "", sortable: false }
            ],
            //Data General
            snackbar: false,
            snackbarText: "",
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                cantidadMaxima: value =>
                    value * 1 <= this.cantidadMaxima ||
                    "La cantidad no puede ser menor al lote existente"
            }
        };
    },
    computed: {
        ...mapState("crudx", ["inProcess", "form"]),

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

        precio: {
            set() {},
            get() {
                if (this.active) {
                    let ganancia =
                        (this.form.utilidades * this.form.costo) / 100;
                    ganancia = ganancia.toFixed(2);

                    this.form.precio =
                        Number(this.form.costo) + Number(ganancia);
                    let pre = Number(this.form.costo) + Number(ganancia);
                    return pre.toFixed(2);
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

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        // Buscar los Proveedores
        findSupplier: async function() {
            this.detailSupplier = [];
            if (this.form.supplier) {
                let response = await this.index({
                    url: "/api/suppliers",
                    buscarProveedor: this.form.supplier,
                    limit: 5
                });
                this.suppliers = response.proveedores;
            }
        },

        // Seleccionar un Proveedor
        selectSupplier(supplier) {
            this.suppliers = [];
            this.detailSupplier = [];

            this.form.supplier = supplier.razonsocial;
            this.form.supplier_id = supplier.id;

            axios
                .get("/api/suppliers/" + supplier.id)
                .then(response => {
                    this.detailSupplier = response.data;
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
                    lote: this.lote,
                    vence: this.vence,
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
            //Establecer Mensaje del Snackbar
            this.snackbarText = this.tipo;
            if (this.$refs.formRemito.validate()) {
                //Guardar Factura
                await this.save({ url: "/api/remitos" });
                //Activar Snackbar
                this.snackbar = true;
                //Reset Formularios
                this.details = [];
                await this.$refs.formDetalles.reset();
                await this.$refs.formRemito.reset();
            }
        },

        //Resetear Factura
        cancelFactura: async function() {
            //Reset Formularios
            this.details = [];
            await this.$refs.formFindSupplier.reset();
            await this.$refs.formFindArticle.reset();
            await this.$refs.formDetalles.reset();
            await this.$refs.formRemito.reset();
        }
    }
};
</script>

<style>
</style>
