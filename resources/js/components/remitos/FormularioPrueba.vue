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
            <v-form ref="formDetalles">
                <!-- Compras Productos -->
                <div>
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 px-3>
                            <!-- Input Buscar Productos -->
                            <v-text-field
                                @keyup="findProducto()"
                                autofocus
                                v-model="form.producto"
                                :disabled="notProveedor"
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
                        <!-- Input Movimiento -->
                        <v-flex xs12 sm4 px-3>
                            <v-select
                                v-model="movimiento"
                                :disabled="notProveedor == false && disabledMovimiento == false ? false : true"
                                :items="movimientos"
                                :rules="[rules.required]"
                                label="Movimiento"
                                box
                            ></v-select>
                        </v-flex>
                    </v-layout>
                </div>
                <!---------------------->
            </v-form>
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
    name: "FormularioPrueba",

    data() {
        return {
            //______________________Data Proveedores________________________//
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
            //____________________Data Lotes Movimientos____________________//
            movimiento: "ALTA",
            movimientos: ["ALTA", "INCREMENTO"],
            disabledMovimiento: false,
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
        ...mapState("crudx", ["form", "inProcess"])
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        //_________________________Methods Proveedores________________________//
        // Buscar los proveedores
        findProveedor: async function() {
            this.detallesProveedor = [];
            this.proveedoresSearchTable = true;
            this.notProveedor = true;
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
            // Reiniciar Cantidad, Precio y el Producto Seleccionado

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
        selectProducto: async function(producto) {
            // Reiniciar la tabla de productos
            this.productos = [];
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
        movimientosControl: async function(producto) {
            console.log(producto);
            this.movimiento = "ALTA";
            if (producto.inventarios.length <= 0) {
                this.disabledMovimiento = true;
            } else {
                this.disabledMovimiento = false;
            }
        }
    }
};
</script>

<style>
</style>
