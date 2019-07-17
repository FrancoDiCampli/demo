<template>
    <div>
        <template>
            <!-- Barra de progreso circular -->
            <div class="loading" v-show="process">
                <v-layout justify-center>
                    <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                </v-layout>
            </div>
        </template>
        <v-form ref="formFactura" @submit.prevent="saveFactura" v-show="!process">
            <!-- Facturas Headers -->
            <div>
                <v-card-title>
                    <v-layout justify-space-between wrap>
                        <v-flex xs12 sm5 mx-1>
                            <h2 class="text-xs-center text-sm-left">Nueva Factura</h2>
                        </v-flex>
                        <v-flex xs12 sm5 mx-1 class="dataFactura text-xs-center text-sm-right">
                            <p>
                                <b>Punto de Venta:</b> 0003
                                <b>Comprobante Nº:</b>
                                {{ numFactura }}
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
            <!---------------------->
            <!-- Facturas Clientes -->
            <div>
                <!-- Formulario -->
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm6 px-3>
                        <!-- Input Clientes -->
                        <v-text-field
                            @keyup="findCliente()"
                            v-model="form.cliente"
                            :rules="[rules.required]"
                            label="Cliente"
                            box
                        ></v-text-field>

                        <!-- Tabla Clientes -->
                        <transition name="expand">
                            <v-data-table
                                v-show="form.cliente && clientes.length > 0"
                                no-data-text="El cliente no se encuentra en la base de datos."
                                hide-actions
                                hide-headers
                                :items="clientes"
                                class="search-table"
                            >
                                <template v-slot:items="cliente">
                                    <tr
                                        @click="selectCliente(cliente.item)"
                                        style="cursor: pointer;"
                                    >
                                        <td>{{ cliente.item.documentounico }}</td>
                                        <td>{{ cliente.item.razonsocial }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </transition>
                    </v-flex>

                    <!-- Input Condición de Venta -->
                    <v-flex xs12 sm6 px-3>
                        <v-select
                            v-model="condicion"
                            @change="verifyCondicion()"
                            :items="condiciones"
                            :rules="[rules.required]"
                            :disabled="form.cliente_id == 1 ? true : false"
                            label="Condición"
                            box
                        ></v-select>
                    </v-flex>
                </v-layout>
                <!-- Detalles Clientes -->
                <v-layout v-if="detallesCliente.cliente" justify-space-around>
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
                                            {{ detallesCliente.cliente.documentounico }}
                                        </p>
                                        <p>
                                            <b>Razón Social:</b>
                                            {{ detallesCliente.cliente.razonsocial }}
                                        </p>
                                        <p>
                                            <b>Condición Frente al IVA:</b>
                                            {{ detallesCliente.cliente.condicioniva }}
                                        </p>
                                        <p>
                                            <b>Domicilio:</b>
                                            {{ detallesCliente.cliente.direccion }}
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
            <!-- Facturas Productos -->
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
                        <!-- Input Cantidad -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                type="number"
                                v-model="cantidad"
                                :disabled="form.producto_id ? false : true"
                                :rules="[rules.maxStock]"
                                @keyup.enter="fillDetalles()"
                                label="Cantidad"
                                box
                            ></v-text-field>
                        </v-flex>

                        <!-- Input Precio -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field v-model="form.precio" label="Precio" disabled box></v-text-field>
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
                                <td class="hidden-xs-only">{{ detalle.item.precio }}</td>
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
            <!-- Facturas Resumen -->
            <br />
            <div>
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm6 px-3>
                        <v-layout justify-space-around wrap>
                            <v-flex xs12>
                                <v-text-field
                                    type="number"
                                    v-model="form.bonificacion"
                                    label="Bonificacion"
                                    box
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    type="number"
                                    v-model="form.recargo"
                                    label="Recargo"
                                    box
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-select
                                    v-model="tipo"
                                    :items="tiposComprobantes"
                                    :rules="[rules.required]"
                                    :disabled="condicion == 'CUENTA CORRIENTE' ? true : false"
                                    label="Tipo Comprobante"
                                    box
                                ></v-select>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 sm6 px-3>
                        <v-layout justify-space-around wrap>
                            <v-flex xs12>
                                <v-text-field
                                    v-model="subtotalFactura"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Subtotal"
                                    box
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                    v-model="total"
                                    disabled
                                    :rules="[rules.required]"
                                    label="Total"
                                    box
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-layout justify-center>
                                    <v-btn
                                        :disabled="inProcess"
                                        @click="cancelFactura()"
                                        outline
                                        color="primary"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :disabled="detalles.length > 0 ? false : true"
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
            <!-- Modal para agregar el comprobante de pago cuando la condicion es Credito/Debito -->
            <v-dialog v-model="comprobanteCreditoDialog" width="400" persistent>
                <v-card>
                    <v-card-title>
                        <h2>¿Estás Seguro?</h2>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>Antes de grabar la factura debe proporcionar el número de comprobante emitido</v-card-text>
                    <v-card-text>
                        <v-text-field
                            v-model="form.compago"
                            label="Nº Comprobante Credito / Debito"
                            box
                        ></v-text-field>
                    </v-card-text>
                    <v-card-text>
                        <v-layout justify-end wrap>
                            <v-btn
                                @click="comprobanteCreditoDialog = false;"
                                :disabled="inProcess"
                                outline
                                color="primary"
                            >Cancelar</v-btn>

                            <v-btn
                                :disabled="inProcess"
                                @click="saveFactura()"
                                color="primary"
                            >Grabar</v-btn>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-dialog>
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

    props: ["mode"],

    data() {
        return {
            //_________________________Data Headers_________________________//
            numFactura: null,

            //_________________________Data Clientes________________________//
            detallesCliente: [],
            clientes: [],
            condiciones: ["CONTADO", "CREDITO / DEBITO", "CUENTA CORRIENTE"],
            condicion: "CONTADO",

            //_________________________Data Productos________________________//
            cantidad: null,
            stock: 0,
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

            //_________________________Data Resumen________________________//
            tiposComprobantes: ["REMITO X", "FACTURA C"],
            tipo: "REMITO X",
            comprobanteCreditoDialog: false,

            //_________________________Data General________________________//
            process: false,
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
        ...mapState("crudx", ["inProcess", "form", "showData"]),

        //_________________________Computed Productos________________________//

        subtotalProdcuto: {
            set() {},
            get() {
                if (this.cantidad) {
                    let sub = this.cantidad * this.form.precio;
                    return sub.toFixed(2);
                } else {
                    return null;
                }
            }
        },

        //_________________________Computed Resumen________________________//
        subtotalFactura: {
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
                    let total = this.subtotalFactura - boni + reca;
                    this.form.total = total.toFixed(2);
                    return total.toFixed(2);
                } else {
                    this.form.total = null;
                    return null;
                }
            }
        }
    },

    created() {
        //___________________________Created Edit___________________________//
        if (this.mode == "edit") {
            let id = Number(localStorage.getItem("presupuestoID"));

            this.getPresupuesto(id);
        }
    },

    mounted() {
        //_________________________Mounted Headers_________________________//
        this.lastFactura();

        //_________________________Mounted Clientes________________________//
        this.form.cliente_id = 1;
        this.form.cliente = "CONSUMIDOR FINAL";
    },

    methods: {
        ...mapActions("crudx", ["index", "show", "save"]),

        //_________________________Methods Edit____________________________//

        getPresupuesto: async function(id) {
            this.process = true;
            let response = await this.show({ url: "/api/presupuestos/" + id });

            if (response.cliente.id != 1) {
                this.selectCliente(response.cliente);
            }

            for (let i = 0; i < response.detalles.length; i++) {
                // Crear un Nuevo Detalle
                let detalle = {
                    articulo_id: response.detalles[i].articulo_id,
                    producto: response.detalles[i].articulo,
                    cantidad: response.detalles[i].cantidad,
                    precio: response.detalles[i].preciounitario,
                    subtotal: response.detalles[i].subtotal
                };

                // Añadir el Detalle al Array de Detalles
                this.detalles.push(detalle);
                this.form.detalle = this.detalles;
            }

            this.form.bonificacion = response.bonificacion;
            this.form.recargo = response.recargo;

            this.process = false;
        },

        //_________________________Methods Headers_________________________//

        // Buscar la ultima factura para establecer el número de la factura actual
        lastFactura: async function() {
            let response = await this.index({
                url: "/api/facturas",
                limit: 1
            });
            this.numFactura = Number(response.facturas[0].numfactura) + 1;
        },

        //_________________________Methods Clientes________________________//

        // Buscar los Clientes
        findCliente: async function() {
            this.detailClient = [];
            if (this.form.cliente == "0") {
                // Establecer Cliente Como Consumidor Final
                this.detallesCliente = [];
                this.form.cliente_id = 1;
                this.form.cliente = "CONSUMIDOR FINAL";
                this.condicion = "CONTADO";
            } else if (this.form.cliente) {
                // Buscar Cliente
                let response = await this.index({
                    url: "/api/clientes",
                    buscarCliente: this.form.cliente,
                    limit: 5
                });
                this.clientes = response.clientes;
            }
        },

        // Seleccionar un Cliente
        selectCliente(cliente) {
            // Reiniciar la Tabla de Clientes y el Detalles
            this.clientes = [];
            this.detallesCliente = [];

            // Establecer la Razon Social y el Id del Cliente en el Formulario
            this.form.cliente = cliente.razonsocial;
            this.form.cliente_id = cliente.id;

            // Establecer el Detalle de Clientes
            axios
                .get("/api/clientes/" + cliente.id)
                .then(response => {
                    this.detallesCliente = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        //_________________________Methods Productos________________________//

        // Buscar Producto
        findProducto: async function() {
            // Reiniciar Cantidad, Precio y Stock
            this.cantidad = null;
            this.form.precio = null;
            this.stock = 0;
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
            // Comprobar si el articulo tiene stock
            if (producto.stock > 0) {
                // Reiniciar la tabla de productos
                this.productos = [];

                // Seleccionar Producto
                this.form.producto_id = producto.id;
                this.form.producto = producto.articulo;
                this.form.precio = producto.precio;

                var find = this.detalles.find(
                    detalle => detalle.producto === producto.articulo
                );

                if (find) {
                    let cantidadExistente = Number(find.cantidad);
                    this.stock = producto.stock - cantidadExistente;
                } else {
                    this.stock = producto.stock;
                }
            }
        },

        //LLenar Array de Detalles
        fillDetalles() {
            if (this.cantidad) {
                var find = this.detalles.find(
                    detalle => detalle.producto === this.form.producto
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
                        articulo_id: this.form.producto_id,
                        producto: this.form.producto,
                        cantidad: this.cantidad,
                        precio: this.form.precio,
                        subtotal: this.subtotalProdcuto
                    };

                    // Añadir el Detalle al Array de Detalles
                    this.detalles.push(detalle);
                    this.form.detalle = this.detalles;
                }

                // Reiniciar el Formulario de Detalles
                this.form.producto_id = null;
                this.$refs.formDetalles.reset();
                this.stock = 0;
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
                    detalle.subtotal = detalle.cantidad * detalle.precio;
                } else {
                    detalle.subtotal = 0;
                }
            });

            this.form.detalle = this.detalles;
        },

        //_________________________Methods Generales________________________//

        //Comprobar el metodo de pago
        verifyCondicion() {
            if (this.condicion == "CUENTA CORRIENTE") {
                this.tipo = "REMITO X";
                this.tiposComprobantes = ["REMITO X", "FACTURA C"];
            } else if (this.condicion == "CREDITO / DEBITO") {
                this.tipo = "FACTURA C";
                this.tiposComprobantes = ["FACTURA C"];
            } else {
                this.tipo = "REMITO X";
                this.tiposComprobantes = ["REMITO X", "FACTURA C"];
            }
        },

        //Guardar Factura
        saveFactura: async function() {
            if (this.condicion == "CREDITO / DEBITO" && !this.form.compago) {
                this.comprobanteCreditoDialog = true;
            } else {
                //Establecer Campos no establecidos
                this.form.condicion = this.condicion;
                this.form.tipo = this.tipo;

                //Establecer Mensaje del Snackbar
                this.snackbarText = this.tipo;
                if (this.$refs.formFactura.validate()) {
                    //Cerrar modal y activar el indicador de carga
                    this.comprobanteCreditoDialog = false;
                    this.process = true;
                    //Guardar Factura
                    let resID = await this.save({ url: "/api/facturas" });
                    //Retornar el pdf de factura
                    if (this.tipo == "REMITO X") {
                        window.open("/api/remitosPDF/" + resID);
                    } else {
                        window.open("/api/facturasPDF/" + resID);
                    }
                    //Activar Snackbar
                    this.snackbar = true;
                    //Reset Formularios
                    this.detalles = [];
                    await this.$refs.formDetalles.reset();
                    await this.$refs.formFactura.reset();
                    //Establecer Valores Predeterminados
                    this.form.cliente_id = 1;
                    this.form.cliente = "CONSUMIDOR FINAL";
                    this.condicion = "CONTADO";
                    this.tipo = "REMITO X";
                    //Desactivar el indicador de carga
                    this.process = false;
                    if (this.mode == "edit") {
                        this.$router.push("/ventas");
                    }
                }
            }
        },

        //Resetear Factura
        cancelFactura: async function() {
            //Reset Formularios
            this.detalles = [];
            await this.$refs.formDetalles.reset();
            await this.$refs.formFactura.reset();
            //Establecer Valores Predeterminados
            this.form.cliente_id = 1;
            this.form.cliente = "CONSUMIDOR FINAL";
            this.condicion = "CONTADO";
            this.tipo = "REMITO X";
        }
    }
};
</script>

<style>
</style>
