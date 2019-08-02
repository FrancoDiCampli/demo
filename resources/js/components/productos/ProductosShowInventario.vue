<template>
    <div>
        <br />
        <!-- Inventarios Header -->
        <v-layout justify-space-around>
            <!-- stock total -->
            <v-flex xs6 px-3 mt-2>
                <h2>Stock: {{ showData.stock }}</h2>
            </v-flex>
            <!-- botón para agregar un nuevo inventario -->
            <v-flex px-3>
                <v-layout justify-end>
                    <v-btn
                        v-show="rol == 'superAdmin' || rol == 'admin'"
                        flat
                        icon
                        color="primary"
                        @click="panelControl()"
                    >
                        <v-icon v-show="!formPanel[0]" size="medium">fas fa-plus</v-icon>
                        <v-icon v-show="formPanel[0]" size="medium">fas fa-times</v-icon>
                    </v-btn>
                </v-layout>
            </v-flex>
        </v-layout>
        <!-- formulario para agregar o modificar un inventario -->
        <v-expansion-panel
            v-show="rol == 'superAdmin' || rol == 'admin'"
            v-model="formPanel"
            expand
        >
            <v-expansion-panel-content expand-icon="fas fa-plus">
                <v-card>
                    <v-card-text>
                        <v-form ref="inventariosForm" @submit.prevent="preventSave()">
                            <v-layout justify-space-around wrap>
                                <!-- input movimiento -->
                                <v-flex xs12 sm4 px-3>
                                    <v-select
                                        v-model="movimiento"
                                        @change="lotesControl()"
                                        :disabled="disabledMovimientos"
                                        :items="movimientos"
                                        :rules="[rules.required]"
                                        label="Movimiento"
                                        box
                                    ></v-select>
                                </v-flex>
                                <!-- input lote -->
                                <v-flex xs12 sm4 px-3>
                                    <v-select
                                        v-model="lote"
                                        @change="findLote()"
                                        :disabled="disabledLote"
                                        :items="lotesDisponibles"
                                        :rules="[rules.required]"
                                        label="Lote"
                                        box
                                    ></v-select>
                                </v-flex>
                                <!-- input cantidad -->
                                <v-flex xs12 sm4 px-3>
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
                                <!-- input vencimiento -->
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
                                                :disabled="disabledInputs"
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
                                <!-- Input Proveeder -->
                                <v-flex xs12 sm6 px-3>
                                    <v-text-field
                                        @keyup="findSuppliers()"
                                        v-model="form.supplier"
                                        :disabled="disabledInputs"
                                        :rules="[rules.required]"
                                        label="Proveedor"
                                        box
                                    ></v-text-field>
                                    <!-- Tabla Proveedores -->
                                    <transition name="expand">
                                        <div v-show="proveedorSearchTable">
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
                                                    v-show="form.supplier && suppliers.length > 0"
                                                    no-data-text="El proveedor no se encuentra en la base de datos."
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
                                            </div>
                                        </div>
                                    </transition>
                                </v-flex>
                            </v-layout>
                            <v-layout justify-center>
                                <v-btn :disabled="inProcess" type="submit" color="primary">
                                    <div
                                        v-if="movimiento == 'ALTA' || movimiento == 'INCREMENTO'"
                                    >Agregar</div>
                                    <div
                                        v-else-if="
                                    movimiento == 'DECREMENTO' ||
                                    movimiento == 'DEVOLUCION' ||
                                    movimiento == 'VENCIMIENTO'
                                "
                                    >Disminuir</div>
                                    <div v-else>Modificar</div>
                                </v-btn>
                            </v-layout>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>

        <!-- Tabla de inventarios -->
        <div v-if="showData.inventarios.length > 0">
            <v-data-table hide-actions :headers="headers" :items="showData.inventarios">
                <template v-slot:items="inventario">
                    <td>{{ inventario.item.cantidad }}</td>
                    <td>{{ inventario.item.lote }}</td>
                    <td class="hidden-xs-only">{{ inventario.item.vencimiento }}</td>
                    <td>{{ inventario.item.proveedor.razonsocial }}</td>
                </template>
            </v-data-table>
        </div>
        <div v-else>
            <br />
            <h2 class="text-xs-center">¡Este producto no tiene inventario!</h2>
            <br />
        </div>

        <!-- dialog verificador al guardar o modificar inventario -->
        <v-dialog v-model="preventSaveDialog" width="750" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>{{ msg }}</v-card-text>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn
                            :disabled="inProcess"
                            @click="preventSaveDialog = false"
                            outline
                            color="primary"
                        >Cancelar</v-btn>
                        <v-btn
                            :loading="inProcess"
                            :disabled="inProcess"
                            @click="saveInventarios()"
                            color="primary"
                        >
                            <div v-if="movimiento == 'ALTA' || movimiento == 'INCREMENTO'">Agregar</div>
                            <div
                                v-else-if="
                                    movimiento == 'DECREMENTO' ||
                                    movimiento == 'DEVOLUCION' ||
                                    movimiento == 'VENCIMIENTO'
                                "
                            >Disminuir</div>
                            <div v-else>Modificar</div>
                        </v-btn>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
// Axios
import axios from "axios";

// Vuex
import { mapState, mapActions, mapMutations } from "vuex";

export default {
    name: "ProductosShowInventario",

    data() {
        return {
            formPanel: [false], //Panel formulario inventario
            modalVencimiento: false,
            alicuota: null,
            alicuotas: [21, 10.5],
            suppliers: [],
            proveedorSearchTable: false,
            movimiento: "ALTA",
            movimientos: [
                "ALTA",
                "INCREMENTO",
                "DEVOLUCION",
                "VENCIMIENTO",
                "DECREMENTO",
                "MODIFICACION"
            ],
            disabledMovimientos: false,
            lotesDisponibles: [],
            lote: null,
            disabledLote: false,
            disabledInputs: false,
            preventSaveDialog: false,
            msg: null,
            cantidadMaxima: 999999999,
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                cantidadMaxima: value =>
                    value * 1 <= this.cantidadMaxima ||
                    "La cantidad no puede ser menor al lote existente"
            },

            headers: [
                { text: "Cantidad", sortable: false },
                { text: "Lote", sortable: false },
                {
                    text: "Vencimiento",
                    sortable: false,
                    class: "hidden-xs-only"
                },
                { text: "Proveedor", sortable: false }
            ] // Header de la tabla de inventarios
        };
    },

    computed: {
        ...mapState("crudx", ["showData", "form", "inProcess"]),
        ...mapState("auth", ["rol"])
    },

    mounted() {
        this.movimientosControl();
        this.lotesControl();
    },

    methods: {
        ...mapActions("crudx", ["index", "save", "show"]),

        log() {
            console.log(this.showData);
        },

        // Activar o desactivar el panel del formulario de inventarios
        panelControl: async function() {
            if (this.formPanel[0]) {
                this.$refs.inventariosForm.reset();
                this.formPanel = [false];
            } else {
                await this.movimientosControl();
                await this.lotesControl();
                this.formPanel = [true];
            }
        },

        movimientosControl() {
            if (this.showData.lotes.lotes.length <= 0) {
                this.movimiento = "ALTA";
                this.disabledMovimientos = true;
                this.lote = this.showData.lotes.proximo;
            } else {
                this.movimiento = "ALTA";
                this.disabledMovimientos = false;
                this.lote = null;
            }
        },

        lotesControl: async function() {
            if (this.movimiento == "ALTA") {
                this.lotesDisponibles = [];
                this.lotesDisponibles.push(this.showData.lotes.proximo);
                this.lote = this.showData.lotes.proximo;
                await this.findLote();
                this.disabledLote = true;
                this.disabledInputs = false;
            } else {
                this.lotesDisponibles = [];
                for (let i = 0; i < this.showData.lotes.lotes.length; i++) {
                    this.lotesDisponibles.push(this.showData.lotes.lotes[i]);
                }
                this.lote = this.showData.lotes.lotes[0];
                await this.findLote();
                this.disabledLote = false;
                this.disabledInputs = true;
            }
        },

        findLote: async function() {
            let response = await this.index({
                url: "/api/inventarios",
                lote: this.lote,
                articulo_id: this.showData.articulo.id
            });
            if (response.length > 0) {
                this.cantidadMaxima = response[0].cantidad;
                this.form.vencimiento = response[0].vencimiento;
                this.form.supplier = response[0].supplier.razonsocial;
                this.form.supplier_id = response[0].supplier.id;
            } else {
                this.cantidadMaxima = 999999999;
                this.form.vencimiento = null;
                this.form.supplier = null;
                this.form.supplier_id = null;
                this.$refs.inventariosForm.resetValidation();
            }
        },

        // Buscar Proveedor
        findSuppliers: async function() {
            this.form.supplier_id = null;
            this.proveedorSearchTable = true;
            let response = await this.index({
                url: "/api/suppliers",
                buscarProveedor: this.form.supplier,
                limit: 5
            });
            this.suppliers = response.proveedores;
        },

        // Seleccionar Proveedor
        selectSupplier(supplier) {
            this.proveedorSearchTable = false;
            this.form.supplier = supplier.razonsocial;
            this.form.supplier_id = supplier.id;
            this.suppliers = [];
        },

        // Establecer el mensage del dialogo y activarlo
        preventSave() {
            if (this.$refs.inventariosForm.validate()) {
                this.preventSaveDialog = true;
                if (this.movimiento == "ALTA") {
                    this.msg =
                        "Se agregará el siguiente lote " +
                        this.lote +
                        " con stock de " +
                        this.form.cantidad +
                        " productos";
                } else if (this.movimiento == "INCREMENTO") {
                    this.msg =
                        "Se agregarán " + this.cantidad + " productos al stock";
                } else if (this.movimiento == "MODIFICACION") {
                    this.msg =
                        "Se modificará la cantidad del lote " +
                        this.lote +
                        " a " +
                        this.cantidad +
                        " productos";
                } else {
                    this.msg =
                        "Se restarán " + this.cantidad + " productos del stock";
                }
            }
        },

        // Guardar Inventarios
        saveInventarios: async function() {
            if (this.$refs.inventariosForm.validate()) {
                this.form.lote = this.lote;
                this.form.movimiento = this.movimiento;
                this.form.articulo_id = this.showData.articulo.id;
                await this.save({ url: "/api/inventarios" });
                this.formPanel = [false];
                await this.show({
                    url: "/api/articulos/" + this.showData.articulo.id
                });
                this.$refs.inventariosForm.reset();
                this.preventSaveDialog = false;
            }
        }
    }
};
</script>

<style>
</style>
