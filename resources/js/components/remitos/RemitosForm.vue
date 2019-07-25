<template>
    <div>
        <template>
            <div class="loading" v-show="process">
                <v-layout justify-center>
                    <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                </v-layout>
            </div>
        </template>
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
                            :rules="[rules.required]"
                            :disabled="proveedorDistinto"
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
                            :rules="[rules.required]"
                            :disabled="proveedorDistinto"
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
            <!-- Compras Productos -->
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
                                :disabled="proveedorDistinto"
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
                        <v-flex xs12 px-3>
                            <v-alert :value="proveedorDistinto" color="error">
                                <div
                                    class="text-xs-center"
                                >Este producto ya tiene un lote con este número asociado a otro proveedor, por favor cambie el número del lote.</div>
                            </v-alert>
                        </v-flex>
                        <!-- Input Lote -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                v-model="form.lote"
                                @keyup="findLote()"
                                :disabled="form.producto_id ? false : true"
                                label="Lote"
                                box
                                :rules="[rules.required]"
                            ></v-text-field>
                        </v-flex>
                        <!-- Input Vencimiento -->
                        <v-flex xs12 sm4 px-3>
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
                                        :disabled="form.producto_id && proveedorDistinto == false ? false : true"
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
                        <!-- Input Movimiento -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field v-model="form.movimiento" label="Movimiento" disabled box></v-text-field>
                        </v-flex>
                        <!-- Input Cantidad -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                type="number"
                                v-model="cantidad"
                                :disabled="form.producto_id && proveedorDistinto == false ? false : true"
                                @keyup.enter="fillDetalles()"
                                label="Cantidad"
                                box
                            ></v-text-field>
                        </v-flex>
                        <!-- Input Precio -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                v-model="form.preciounitario"
                                label="Precio de Compra"
                                :disabled="form.producto_id && proveedorDistinto == false ? false : true"
                                box
                            ></v-text-field>
                        </v-flex>
                        <!-- Input Subtotal -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field v-model="subtotalProdcuto" label="Subtotal" disabled box></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-form>
                <!-- Tabla Detalles -->
                <v-layout justify-space-around>
                    <v-flex xs12 px-3>
                        <v-data-table :headers="detallesHeader" :items="detalles" hide-actions>
                            <template v-slot:items="detalle">
                                <td>
                                    <div v-if="detalle.item.actual_supplier == false">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-icon
                                                    color="error"
                                                    dark
                                                    v-on="on"
                                                >fas fa-exclamation-circle</v-icon>
                                            </template>
                                            <span>Este producto ya tiene un lote con este número asociado a otro proveedor, por favor cambie el número del lote.</span>
                                        </v-tooltip>
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
            </div>
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
                                    :disabled="proveedorDistinto"
                                    label="Bonificacion"
                                    box
                                ></v-text-field>
                                <Error tag="bonificacion"></Error>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    type="number"
                                    v-model="form.recargo"
                                    :disabled="proveedorDistinto"
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
                                        :disabled="detalles.length > 0 && proveedorDistinto == false && detallesActualSupllier == false ? false : true"
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
            //______________________Data Proveedores________________________//
            detallesProveedor: [],
            proveedores: [],
            proveedoresSearchTable: false,
            //_________________________Data General________________________//
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            },
            //_________________________Data Productos________________________//
            cantidad: null,
            modalVencimiento: false,
            productos: [],
            detalles: [],
            selected: null,
            productosHeaders: [
                { text: "Codigo", sortable: false, class: "hidden-xs-only" },
                { text: "Articulo", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Stock", sortable: false }
            ],
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
            productosSearchTable: false,
            formPanel: [0],
            proveedorDistinto: false,
            assignProveedor: false,
            process: false
        };
    },
    components: {
        Error
    },
    computed: {
        ...mapState("crudx", ["inProcess", "form"]),
        //_________________________Computed Productos________________________//
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

        detallesActualSupllier() {
            for (let i = 0; i < this.detalles.length; i++) {
                if (this.detalles[i].supplier_id != this.form.supplier_id) {
                    return true;
                }
            }

            return false;
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
    mounted() {
        //_________________________Methods Productos________________________//
        this.form.movimiento = "ALTA";
    },
    methods: {
        ...mapActions("crudx", ["index", "save"]),
        //_________________________Methods Proveedores________________________//
        // Buscar los proveedores
        findProveedor: async function() {
            this.detallesProveedor = [];
            this.proveedoresSearchTable = true;
            if (this.form.supplier != null && this.form.supplier != "") {
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
        // Seleccionar un Cliente
        selectProveedor(proveedor) {
            // Reiniciar la Tabla de proveedores y el detalle
            this.proveedores = [];
            this.detallesProveedor = [];
            this.proveedoresSearchTable = false;
            // Establecer la Razon Social y el Id del Proveedor en el Formulario
            this.form.supplier = proveedor.razonsocial;
            this.form.supplier_id = proveedor.id;
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
        //_________________________Methods Productos________________________//
        // Buscar Producto
        findProducto: async function() {
            // Reiniciar Cantidad y Precio
            this.cantidad = null;
            this.form.precio = null;
            // Activar Tabla de Busqueda
            this.productosSearchTable = true;
            // Buscar Productos
            if (this.form.producto) {
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
            // Desactivar Tabla de Busqueda
            this.productosSearchTable = false;
            // Seleccionar Producto
            this.form.producto_id = producto.id;
            this.form.producto = producto.articulo;
            this.form.preciounitario = producto.precio;
        },
        // Buscar Lote
        findLote: async function() {
            if (this.form.lote) {
                this.process = true;
                if (this.form.lote.length > 0) {
                    let response = await this.index({
                        url: "/api/inventarios",
                        lote: this.form.lote,
                        articulo_id: this.form.producto_id
                    });
                    this.process = false;
                    if (response.length > 0) {
                        if (this.form.supplier_id) {
                            if (
                                this.form.supplier_id != response[0].supplier.id
                            ) {
                                this.proveedorDistinto = true;
                            } else {
                                this.proveedorDistinto = false;
                                this.form.lote = response[0].lote;
                                this.form.vencimiento = response[0].vencimiento;
                                this.form.movimiento = "INCREMENTO";
                            }
                        } else {
                            this.proveedorDistinto = false;
                            this.form.lote = response[0].lote;
                            this.form.vencimiento = response[0].vencimiento;
                            this.form.supplier =
                                response[0].supplier.razonsocial;
                            this.form.supplier_id = response[0].supplier.id;
                            this.form.movimiento = "INCREMENTO";
                        }
                        this.assignProveedor = true;
                    } else {
                        this.proveedorDistinto = false;
                        this.form.movimiento = "ALTA";
                        this.assignProveedor = false;
                    }
                }
            }
        },
        // Verficar Proveedor
        checkSupplier() {
            for (let i = 0; i < this.detalles.length; i++) {
                if (this.form.supplier_id) {
                    if (this.detalles[i].supplier_id == null) {
                        this.detalles[i].supplier_id = this.form.supplier_id;
                        this.detalles[i].actual_supplier = true;
                    } else {
                        if (
                            this.detalles[i].supplier_id !=
                            this.form.supplier_id
                        ) {
                            if (this.detalles[i].assignProveedor) {
                                this.detalles[i].actual_supplier = false;
                            } else {
                                this.detalles[
                                    i
                                ].supplier_id = this.form.supplier_id;
                                this.detalles[i].actual_supplier = true;
                            }
                        } else {
                            this.detalles[i].actual_supplier = true;
                        }
                    }
                } else {
                    this.detalles[i].supplier_id = null;
                    this.detalles[i].actual_supplier = true;
                }
            }

            console.log(this.detalles);
        },
        //LLenar Array de Detalles
        fillDetalles() {
            if (this.cantidad) {
                var find = this.detalles.find(
                    detalle =>
                        detalle.producto === this.form.producto &&
                        detalle.lote === this.form.lote
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
                        lote: this.form.lote,
                        vencimiento: this.form.vencimiento,
                        preciounitario: this.form.preciounitario,
                        subtotal: this.subtotalProdcuto,
                        supplier_id: null,
                        actual_supplier: true,
                        assignProveedor: this.assignProveedor
                    };
                    // Asignar el proveedor al detalle (si existe uno seleccionado)
                    if (this.form.supplier_id) {
                        detalle.supplier_id = this.form.supplier_id;
                    }
                    // Añadir el Detalle al Array de Detalles
                    this.detalles.push(detalle);
                    this.form.detalle = this.detalles;
                }
                // Reiniciar el Formulario de Detalles
                this.form.producto_id = null;
                this.$refs.formDetalles.reset();

                this.checkSupplier();
            }
        },
        // Borrar un Detalle del Array
        removeDetalle(prodcuto) {
            let index = this.detalles.indexOf(prodcuto);
            this.detalles.splice(index, 1);
            this.form.detalle = this.detalles;
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
            this.form.detalle = this.detalles;
        },
        //_________________________Methods Generales________________________//
        // Imprimir Compra
        remitosPDF: function(id) {
            axios({
                url: "/api/remitosPDF/" + id,
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
                let resID = await this.save({ url: "/api/suppliers" });
                //Imprimir PDF de Compras
                this.remitosPDF(resID);
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
