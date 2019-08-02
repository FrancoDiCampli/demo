<template>
    <div>
        <v-form ref="formCompra" @submit.prevent="saveCompra">
            <!-- Compras Headers -->
            <div>
                <v-card-title>
                    <v-layout>
                        <v-flex mx-1>
                            <h2 class="text-xs-center text-sm-left">Nueva Compra</h2>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <br />
            </div>
            <!---------------------->
            <!-- Compras Proveedor -->
            <div>
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm6 px-3>
                        <!-- Input Proveedores -->
                        <v-text-field
                            @keyup="findProveedor()"
                            v-model="form.supplier"
                            :hint="proveedoresSearchTable ? '' : 'Escriba para buscar un proveedor'"
                            persistent-hint
                            clearable
                            clear-icon="fas fa-times"
                            :rules="[rules.required]"
                            label="Proveedor"
                            box
                        ></v-text-field>
                        <Error tag="supplier_id"></Error>
                        <!-- Tabla Proveedores -->
                        <transition name="expand">
                            <div v-show="proveedoresSearchTable">
                                <div v-if="inProcess" class="search-table">
                                    <v-layout justify-center>
                                        <v-flex xs12 pa-3>
                                            <v-layout justify-center>
                                                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </div>
                                <div v-else>
                                    <v-data-table
                                        no-data-text="El proveedor no se encuentra en la base de datos."
                                        hide-actions
                                        hide-headers
                                        :items="proveedores"
                                        class="search-table"
                                    >
                                        <template v-slot:items="proveedor">
                                            <tr
                                                @click="selectProveedor(proveedor.item)"
                                                style="cursor: pointer;"
                                            >
                                                <td>{{ proveedor.item.cuit }}</td>
                                                <td>{{ proveedor.item.razonsocial }}</td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </transition>
                    </v-flex>

                    <!-- Input Nº Remito -->
                    <v-flex xs12 sm6 px-3>
                        <v-text-field
                            v-model="form.numremito"
                            :disabled="notProveedor"
                            :rules="[rules.required]"
                            label="Nº Remito"
                            box
                        ></v-text-field>
                        <Error tag="numremito"></Error>
                    </v-flex>
                </v-layout>

                <!-- Detalles Proveedor -->
                <v-layout v-if="detallesProveedor.proveedor" justify-space-around>
                    <v-flex xs12 px-3>
                        <template>
                            <v-expansion-panel class="elevation-0 expansion-border">
                                <v-expansion-panel-content>
                                    <template v-slot:header>
                                        <div>Más Detalles</div>
                                    </template>
                                    <v-card-text>
                                        <p>
                                            <b>CUIT:</b>
                                            {{ detallesProveedor.proveedor.cuit }}
                                        </p>
                                        <p>
                                            <b>Razón Social:</b>
                                            {{ detallesProveedor.proveedor.razonsocial }}
                                        </p>
                                        <p>
                                            <b>Domicilio:</b>
                                            {{ detallesProveedor.proveedor.direccion }}
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
            <v-form ref="formDetalles" @submit.prevent="fillDetalles()">
                <!-- Compras Productos -->
                <div>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 px-3>
                            <!-- Input Buscar Productos -->
                            <v-text-field
                                @keyup="findProducto()"
                                autofocus
                                v-model="form.producto"
                                :hint="productosSearchTable ? '' : 'Escriba para buscar un producto'"
                                persistent-hint
                                clearable
                                clear-icon="fas fa-times"
                                :disabled="blockProducto"
                                label="Producto"
                                box
                            ></v-text-field>

                            <!-- Tabla Buscar Productos -->
                            <transition>
                                <div v-show="productosSearchTable">
                                    <div v-if="inProcess" class="search-table">
                                        <v-layout justify-center>
                                            <v-flex xs12 pa-3>
                                                <v-layout justify-center>
                                                    <v-progress-circular
                                                        indeterminate
                                                        color="primary"
                                                    ></v-progress-circular>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                    <div v-else>
                                        <v-data-table
                                            no-data-text="El producto no se encuentra en la base de datos."
                                            hide-actions
                                            :headers="productosHeaders"
                                            :items="productos"
                                            class="search-table"
                                        >
                                            <template v-slot:items="producto">
                                                <tr
                                                    @click="selectProducto(producto.item)"
                                                    style="cursor: pointer;"
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
                                    </div>
                                </div>
                            </transition>
                        </v-flex>
                    </v-layout>
                </div>
                <!---------------------->
                <!-- Compras Lotes Movimientos -->
                <div>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 px-3 mb-3 v-show="proveedorDistinto">
                            <v-alert :value="proveedorDistinto" color="error">
                                <div
                                    class="text-xs-center"
                                >Este producto ya tiene un lote con este número asociado a otro proveedor, por favor cambie el número del lote.</div>
                            </v-alert>
                        </v-flex>
                        <!-- Input Movimiento -->
                        <v-flex xs12 sm4 px-3>
                            <v-select
                                v-model="movimiento"
                                @change="lotesControl()"
                                :disabled="blockMovimiento"
                                :items="movimientos"
                                :rules="[rules.required]"
                                label="Movimiento"
                                box
                            ></v-select>
                        </v-flex>
                        <!-- Input Lote -->
                        <v-flex xs12 sm4 px-3>
                            <v-select
                                v-model="lote"
                                @change="findLote()"
                                :disabled="blockLote"
                                :items="lotes"
                                :rules="[rules.required]"
                                label="Lote"
                                box
                            ></v-select>
                        </v-flex>
                        <!-- Input Cantidad -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                type="number"
                                v-model="cantidad"
                                :disabled="blockDetalles"
                                label="Cantidad"
                                box
                            ></v-text-field>
                        </v-flex>
                        <!-- Input Vencimiento -->
                        <v-flex xs12 sm4 px-3>
                            <v-dialog
                                ref="vencimiento"
                                v-model="modalVencimiento"
                                :return-value.sync="vencimiento"
                                persistent
                                lazy
                                full-width
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="vencimiento"
                                        @keyup.enter="fillDetalles()"
                                        label="Fecha de Vencimiento"
                                        :disabled="blockDetalles"
                                        :rules="[rules.required]"
                                        box
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="vencimiento"
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
                                        @click="$refs.vencimiento.save(vencimiento)"
                                    >OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>
                        <!-- Input Precio -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                v-model="form.preciounitario"
                                label="Precio de Compra"
                                :disabled="blockDetalles"
                                box
                            ></v-text-field>
                        </v-flex>
                        <!-- Input Subtotal -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field v-model="subtotalProdcuto" label="Subtotal" disabled box></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout justify-center style="margin-top: -20px;">
                        <v-btn type="submit" color="primary" flat>Agregar</v-btn>
                    </v-layout>
                </div>
                <!---------------------->
            </v-form>
            <!-- Tabla Detalles -->
            <v-layout justify-space-around wrap>
                <v-flex xs12 px-3 mb-3 v-show="detallesProveedorDistinto">
                    <v-alert :value="detallesProveedorDistinto" color="error">
                        <div
                            class="text-xs-center"
                        >Uno o más productos del detalle ya tienen un lote asociado a otro proveedor, por favor elimine el detalle que que presente algún conflicto o cambie el proveedor.</div>
                    </v-alert>
                </v-flex>
                <v-flex xs12 px-3>
                    <v-data-table :headers="detallesHeader" :items="detalles" hide-actions>
                        <template v-slot:items="detalle">
                            <td>
                                <div v-if="detalle.item.proveedorDistinto">
                                    <v-icon color="error" dark>fas fa-exclamation-circle</v-icon>
                                </div>
                                <div v-else @click="detalle.selected = !detalle.selected;">
                                    <v-checkbox
                                        :input-value="detalle.selected"
                                        color="primary"
                                        hide-details
                                    ></v-checkbox>
                                </div>
                            </td>
                            <td>{{ detalle.item.producto }}</td>
                            <td>
                                <v-edit-dialog :return-value.sync="detalle.item.cantidad" lazy>
                                    {{ detalle.item.cantidad }}
                                    <template v-slot:input>
                                        <v-text-field
                                            v-model="detalle.item.cantidad"
                                            label="Cantidad"
                                            single-line
                                            @keyup="updateDetalle()"
                                        ></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </td>
                            <td class="hidden-xs-only">{{ detalle.item.lote }}</td>
                            <td class="hidden-sm-and-down">{{ detalle.item.preciounitario }}</td>
                            <td>{{ detalle.item.subtotal }}</td>
                            <td style="padding: 0px;">
                                <v-btn
                                    @click="removeDetalle(detalle.item)"
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
            <!---------------------->
            <!-- Compras Resumen -->
            <br />
            <div>
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm6 px-3>
                        <v-layout justify-space-around wrap>
                            <v-flex xs12>
                                <v-text-field
                                    type="number"
                                    v-model="form.bonificacion"
                                    :disabled="blockProducto"
                                    label="Bonificacion"
                                    box
                                ></v-text-field>
                                <Error tag="bonificacion"></Error>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    type="number"
                                    v-model="form.recargo"
                                    :disabled="blockProducto"
                                    label="Recargo"
                                    box
                                ></v-text-field>
                                <Error tag="recargo"></Error>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    value="REMITO DE COMPRA"
                                    label="Tipo Comprobante"
                                    disabled
                                    box
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 sm6 px-3>
                        <v-layout justify-space-around wrap>
                            <v-flex xs12>
                                <v-text-field
                                    v-model="subtotalCompra"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Subtotal"
                                    box
                                ></v-text-field>
                                <Error tag="subtotal"></Error>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    v-model="total"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Total"
                                    box
                                ></v-text-field>
                                <Error tag="total"></Error>
                            </v-flex>
                            <v-flex xs12>
                                <v-layout justify-center>
                                    <v-btn
                                        :disabled="inProcess"
                                        @click="cancelCompra()"
                                        outline
                                        color="primary"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :disabled="blockSave"
                                        :loading="inProcess"
                                        type="submit"
                                        color="primary"
                                    >Guardar</v-btn>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
                <br />
            </div>
            <!---------------------->
        </v-form>
    </div>
</template>

<script>
// Components
import Error from "../../crudx/error.vue";

// Vuex
import { mapState, mapActions } from "vuex";

// Axios
import axios from "axios";

export default {
    name: "RemitosForm",

    data() {
        return {
            //________________________Data Proveedores________________________//
            detallesProveedor: [],
            proveedores: [],
            proveedoresSearchTable: false,
            notProveedor: true,
            //_________________________Data Productos________________________//
            productosSearchTable: false,
            productos: [],
            productosHeaders: [
                { text: "Codigo", sortable: false, class: "hidden-xs-only" },
                { text: "Articulo", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Stock", sortable: false }
            ],
            productoSelected: null,
            //____________________Data Lotes Movimientos____________________//
            movimiento: "ALTA",
            movimientos: ["ALTA", "INCREMENTO"],
            disabledMovimiento: false,
            lote: null,
            lotes: [],
            disabledLote: false,
            proveedorDistinto: false,
            //_________________________Data Detalles________________________//
            cantidad: null,
            vencimiento: null,
            modalVencimiento: false,
            detalles: [],
            detallesHeader: [
                { text: "Modificar", sortable: false },
                { text: "Articulo", sortable: false },
                { text: "Cantidad", sortable: false },
                {
                    text: "Lote",
                    sortable: false,
                    class: "hidden-xs-only"
                },
                {
                    text: "Precio",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
                { text: "Subtotal", sortable: false },
                { text: "", sortable: false }
            ],
            //_________________________Data General________________________//
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    components: {
        Error
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"]),

        //_________________________Computed Disabled________________________//
        blockProducto() {
            if (
                !this.notProveedor &&
                !this.proveedorDistinto &&
                !this.detallesProveedorDistinto
            ) {
                return false;
            } else {
                return true;
            }
        },

        blockMovimiento() {
            if (
                !this.notProveedor &&
                this.productoSelected != null &&
                !this.disabledMovimiento &&
                !this.detallesProveedorDistinto
            ) {
                return false;
            } else {
                return true;
            }
        },

        blockLote() {
            if (
                !this.notProveedor &&
                this.productoSelected != null &&
                !this.disabledLote &&
                !this.detallesProveedorDistinto
            ) {
                return false;
            } else {
                return true;
            }
        },

        blockDetalles() {
            if (
                this.productoSelected != null &&
                !this.proveedorDistinto &&
                !this.detallesProveedorDistinto
            ) {
                return false;
            } else {
                return true;
            }
        },

        blockSave() {
            if (
                !this.notProveedor &&
                !this.proveedorDistinto &&
                !this.detallesProveedorDistinto &&
                this.detalles.length > 0
            ) {
                return false;
            } else {
                return true;
            }
        },

        //_________________________Computed Detalles________________________//
        subtotalProdcuto: {
            set() {},
            get() {
                if (this.cantidad) {
                    let sub = this.cantidad * this.form.preciounitario;
                    return sub.toFixed(2);
                } else {
                    return null;
                }
            }
        },

        detallesProveedorDistinto() {
            if (this.detalles.length > 0) {
                for (let i = 0; i < this.detalles.length; i++) {
                    if (this.detalles[i].proveedorDistinto == true) {
                        return true;
                    }
                }
            } else {
                return false;
            }
        },

        //_________________________Computed Resumen________________________//
        subtotalCompra: {
            set() {},
            get() {
                if (this.detalles.length > 0) {
                    let sub = 0;
                    for (let i = 0; i < this.detalles.length; i++) {
                        sub += this.detalles[i].subtotal * 1;
                    }
                    this.form.subtotal = sub.toFixed(2);
                    return sub.toFixed(2);
                } else {
                    this.form.subtotal = null;
                    return null;
                }
            }
        },
        total: {
            set() {},
            get() {
                if (this.subtotalCompra != null) {
                    let boni = 0;
                    let reca = 0;
                    if (this.form.bonificacion) {
                        if (this.form.bonificacion.length > 0) {
                            boni =
                                (this.form.bonificacion * this.subtotalCompra) /
                                100;
                        }
                    }
                    if (this.form.recargo) {
                        if (this.form.recargo.length > 0) {
                            reca =
                                (this.form.recargo * this.subtotalCompra) / 100;
                        }
                    }
                    let total = this.subtotalCompra - boni + reca;
                    this.form.total = total.toFixed(2);
                    return total.toFixed(2);
                } else {
                    this.form.total = null;
                    return null;
                }
            }
        }
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        //_________________________Methods Proveedores________________________//
        // Buscar los proveedores
        findProveedor: async function() {
            this.detallesProveedor = [];
            this.notProveedor = true;
            if (this.form.supplier) {
                this.proveedoresSearchTable = true;
                let response = await this.index({
                    url: "/api/suppliers",
                    buscarProveedor: this.form.supplier,
                    limit: 5
                });
                this.proveedores = response.proveedores;
            } else {
                this.proveedores = [];
            }
        },
        // Seleccionar un Proveedor
        selectProveedor(proveedor) {
            // Reiniciar la Tabla de proveedores y el detalle
            this.proveedores = [];
            this.detallesProveedor = [];
            this.proveedoresSearchTable = false;
            // Establecer la Razon Social y el Id del Proveedor en el Formulario
            this.form.supplier = proveedor.razonsocial;
            this.form.supplier_id = proveedor.id;
            this.notProveedor = false;
            this.checkSupplier();
            // Establecer el Detalle de Proveedors
            axios
                .get("/api/suppliers/" + proveedor.id)
                .then(response => {
                    this.detallesProveedor = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        // Verificar si el proveedor es igual al seleccionado en cada detalle
        checkSupplier() {
            if (this.detalles.length > 0) {
                for (let i = 0; i < this.detalles.length; i++) {
                    if (this.detalles[i].supplier_id != this.form.supplier_id) {
                        if (this.detalles[i].movimiento == "ALTA") {
                            this.detalles[
                                i
                            ].supplier_id = this.form.supplier_id;
                            this.detalles[i].proveedorDistinto = false;
                        } else {
                            this.detalles[i].proveedorDistinto = true;
                        }
                    } else {
                        this.detalles[i].proveedorDistinto = false;
                    }
                }
            }
        },

        //_________________________Methods Productos________________________//
        // Buscar Producto
        findProducto: async function() {
            this.productoSelected = null;
            // Buscar Productos
            if (this.form.producto) {
                // Activar Tabla de Busqueda
                this.productosSearchTable = true;
                let response = await this.index({
                    url: "/api/articulos",
                    buscarArticulo: this.form.producto,
                    limit: 5
                });
                this.productos = response.articulos;
            }
        },
        // Seleccionar Producto
        selectProducto(producto) {
            // Reiniciar la tabla de productos
            this.productos = [];
            this.productoSelected = producto;
            // Controlar el movimiento
            this.movimientosControl(producto);
            // Desactivar Tabla de Busqueda
            this.productosSearchTable = false;
            // Seleccionar Producto
            this.form.producto_id = producto.id;
            this.form.producto = producto.articulo;
            this.form.preciounitario = producto.precio;
        },

        //____________________Methods Lotes Movimientos_____________________//
        // Controlar los movimientos disponibles
        movimientosControl(producto) {
            this.movimiento = "ALTA";
            if (producto.inventarios.length <= 0) {
                this.disabledMovimiento = true;
            } else {
                this.disabledMovimiento = false;
            }
            this.lotesControl();
        },

        lotesControl: async function() {
            // Comprobar el tipo de movimiento
            if (this.movimiento == "ALTA") {
                // Comprobar si el articulo tiene inventarios o no
                if (this.productoSelected.inventarios.length <= 0) {
                    // Si no tiene inventarios asignar el lote 1
                    this.lotes = [1];
                    this.lote = 1;
                    this.disabledLote = true;
                } else {
                    // Si tiene inventarios asignar el proximo lote
                    let ultimoLote = this.productoSelected.inventarios[
                        this.productoSelected.inventarios.length - 1
                    ].lote;
                    let proximoLote = Number(ultimoLote) + 1;
                    this.lotes = [proximoLote];
                    this.lote = proximoLote;
                    this.disabledLote = true;
                }
            } else {
                // En caso de incremento asignar todos los lotes al select
                this.lotes = [];
                for (
                    let i = 0;
                    i < this.productoSelected.inventarios.length;
                    i++
                ) {
                    this.lotes.push(this.productoSelected.inventarios[i].lote);
                }
                this.lote = this.productoSelected.inventarios[0].lote;
                // await this.findLote();
                this.disabledLote = false;
            }

            await this.findLote();
        },

        // Buscar el lote
        findLote: async function() {
            let response = await this.index({
                url: "/api/inventarios",
                lote: this.lote,
                articulo_id: this.form.producto_id
            });
            if (response.length > 0) {
                if (this.form.supplier_id != response[0].supplier.id) {
                    this.proveedorDistinto = true;
                } else {
                    this.proveedorDistinto = false;
                }
                this.vencimiento = response[0].vencimiento;
            } else {
                this.proveedorDistinto = false;
                this.vencimiento = null;
                this.$refs.formDetalles.resetValidation();
            }
        },

        //____________________Methods Detalles_____________________//
        //LLenar Array de Detalles
        fillDetalles() {
            if (this.$refs.formDetalles.validate()) {
                var find = this.detalles.find(
                    detalle =>
                        detalle.producto === this.form.producto &&
                        detalle.lote === this.lote
                );

                if (find) {
                    // Buscar el indice del la tabla detalles que coicide con el detalle existente
                    let indexDetalle = this.detalles.indexOf(find);
                    // Sumar a la cantidad del detalle existente la nueva cantidad
                    let nuevaCantidad =
                        Number(this.detalles[indexDetalle].cantidad) +
                        Number(this.cantidad);
                    // Actualizar el detalle
                    this.detalles[indexDetalle].cantidad = nuevaCantidad;
                    this.detalles[indexDetalle].subtotal =
                        Number(this.detalles[indexDetalle].subtotal) *
                        nuevaCantidad;
                } else {
                    // Crear un Nuevo Detalle
                    let detalle = {
                        selected: false,
                        articulo_id: this.form.producto_id,
                        producto: this.form.producto,
                        cantidad: this.cantidad,
                        lote: this.lote,
                        vencimiento: this.vencimiento,
                        preciounitario: this.form.preciounitario,
                        subtotal: this.subtotalProdcuto,
                        supplier_id: this.form.supplier_id,
                        proveedorDistinto: false,
                        movimiento: this.movimiento
                    };
                    // Añadir el Detalle al Array de Detalles
                    this.detalles.push(detalle);
                    this.form.detalle = this.detalles;
                }
                // Reiniciar el Formulario de Detalles
                this.form.producto_id = null;
                this.productoSelected = null;
                this.$refs.formDetalles.reset();
                this.checkSupplier();
            }
        },
        // Borrar un Detalle del Array
        removeDetalle(prodcuto) {
            let index = this.detalles.indexOf(prodcuto);
            this.detalles.splice(index, 1);
            this.form.detalle = this.detalles;
            this.checkSupplier();
        },
        //Actualizar cantidad y subtotal de un detalle
        updateDetalle() {
            this.detalles.forEach(function(detalle) {
                if (detalle.cantidad.length > 0) {
                    detalle.subtotal =
                        detalle.cantidad * detalle.preciounitario;
                } else {
                    detalle.subtotal = 0;
                }
            });
        },

        //_________________________Methods Generales________________________//
        // Imprimir Compra
        comprasPDF: function(id) {
            axios({
                url: "/api/comprasPDF/" + id,
                method: "GET",
                responseType: "blob"
            }).then(response => {
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "compra" + id + ".pdf");
                document.body.appendChild(link);
                link.click();
            });
        },
        //Guardar Compra
        saveCompra: async function() {
            if (this.$refs.formCompra.validate()) {
                //Guardar Compras
                let resID = await this.save({ url: "/api/remitos" });
                //Imprimir PDF de Compras
                this.comprasPDF(resID);
                //Reset Formularios
                this.detalles = [];
                await this.$refs.formDetalles.reset();
                await this.$refs.formCompra.reset();
                this.$router.push("/compras");
            }
        },
        //Resetear Compra
        cancelCompra: async function() {
            //Reset Formularios
            this.detalles = [];
            await this.$refs.formDetalles.reset();
            await this.$refs.formCompra.reset();
        }
    }
};
</script>

<style>
</style>
