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
        <v-form v-show="!process" ref="formPresupuesto" @submit.prevent="savePresupuesto">
            <!-- Facturas Headers -->
            <div>
                <v-card-title>
                    <v-layout justify-space-between wrap>
                        <v-flex xs12 sm5 mx-1>
                            <h2 class="text-xs-center text-sm-left">Nuevo Presupuesto</h2>
                        </v-flex>
                        <v-flex xs12 sm5 mx-1 class="dataPresupuesto text-xs-center text-sm-right">
                            <p>
                                <b>Presupuesto Nº:</b>
                                {{ numPresupuesto }}
                            </p>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <br />
            </div>
            <!---------------------->
            <!-- Presupuestos Clientes -->
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

                    <!-- Input Vencimiento -->
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
                                <v-btn flat color="primary" @click="modalVencimiento = false">Cancel</v-btn>
                                <v-btn
                                    flat
                                    color="primary"
                                    @click="$refs.vencimiento.save(form.vencimiento)"
                                >OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
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
            <!-- Presupuestos Productos -->
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
                            </transition>
                        </v-flex>
                        <!-- Input Cantidad -->
                        <v-flex xs12 sm4 px-3>
                            <v-text-field
                                type="number"
                                v-model="cantidad"
                                :disabled="form.producto_id ? false : true"
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
            <!-- Presupuestos Resumen -->
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
                                <v-text-field
                                    value="PRESUPUESTO"
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
                                        @click="cancelPresupuesto()"
                                        outline
                                        color="primary"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :disabled="detalles.length > 0 ? false : true"
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
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "FacturasUnique",

    data() {
        return {
            //_________________________Data Headers_________________________//
            numPresupuesto: null,
            //_________________________Data Clientes________________________//
            detallesCliente: [],
            clientes: [],
            modalVencimiento: false,

            //_________________________Data Productos________________________//
            cantidad: null,
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

            //_________________________Data General________________________//
            process: false,
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess", "form"]),

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

    mounted() {
        //_________________________Mounted Headers_________________________//
        this.lastPresupuesto();

        //_________________________Mounted Clientes________________________//
        this.form.cliente_id = 1;
        this.form.cliente = "CONSUMIDOR FINAL";
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        //_________________________Methods Headers_________________________//

        // Buscar el ultimo presupuesto para establecer el número del presupuesto actual
        lastPresupuesto: async function() {
            let response = await this.index({
                url: "/api/presupuestos",
                limit: 1
            });
            this.numPresupuesto =
                Number(response.presupuestos[0].numpresupuesto) + 1;
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
            // Reiniciar Cantidad y Precio
            this.cantidad = null;
            this.form.precio = null;
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

            // Seleccionar Producto
            this.form.producto_id = producto.id;
            this.form.producto = producto.articulo;
            this.form.precio = producto.precio;
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

        //Guardar Factura
        savePresupuesto: async function() {
            if (this.$refs.formPresupuesto.validate()) {
                this.process = true;
                //Guardar Presupuestos
                await this.save({ url: "/api/presupuestos" });
                //Reset Formularios
                this.detalles = [];
                await this.$refs.formDetalles.reset();
                await this.$refs.formPresupuesto.reset();
                this.process = false;
                this.$router.push("/presupuestos");
            }
        },

        //Resetear Factura
        cancelPresupuesto: async function() {
            //Reset Formularios
            this.detalles = [];
            await this.$refs.formDetalles.reset();
            await this.$refs.formFactura.reset();
            //Establecer Valores Predeterminados
            this.form.cliente_id = 1;
            this.form.cliente = "CONSUMIDOR FINAL";
        }
    }
};
</script>

<style>
</style>