<template>
    <div>
        <br />
        <v-layout justify-space-around>
            <v-flex xs6 px-3 mt-2>
                <h2>Stock: {{ showData.stock }}</h2>
            </v-flex>
            <v-flex>
                <v-layout justify-end px-3>
                    <v-btn flat icon color="primary" @click="panelControl()">
                        <v-icon v-show="!formPanel[0]" size="medium">fas fa-plus</v-icon>
                        <v-icon v-show="formPanel[0]" size="medium">fas fa-times</v-icon>
                    </v-btn>
                </v-layout>
            </v-flex>
        </v-layout>
        <v-expansion-panel v-model="formPanel" expand>
            <v-expansion-panel-content expand-icon="fas fa-plus">
                <v-card>
                    <v-card-text>
                        <v-form ref="inventariosForm" @submit.prevent="preventSave()">
                            <v-layout justify-space-around wrap>
                                <v-flex xs12 sm4 px-3>
                                    <v-text-field
                                        v-model="form.lote"
                                        @keyup="findLote()"
                                        label="Lote"
                                        box
                                        :rules="[rules.required]"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 px-3>
                                    <v-text-field
                                        v-model="form.cantidad"
                                        label="Cantidad"
                                        box
                                        :rules="[rules.required]"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 px-3>
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
                                        ref="nacimiento"
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
                                                @click="$refs.nacimiento.save(form.vencimiento)"
                                            >OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-flex>
                                <v-flex xs12 sm6 px-3>
                                    <!-- Input Clientes -->
                                    <v-text-field
                                        @keyup="findSuppliers()"
                                        v-model="form.supplier"
                                        :rules="[rules.required]"
                                        append-icon="fas fa-caret-down"
                                        label="Proveedor"
                                        box
                                    ></v-text-field>

                                    <!-- Tabla Clientes -->
                                    <transition name="expand">
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
                                    </transition>
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
                            <v-layout justify-center>
                                <v-btn :disabled="process" type="submit" color="primary">
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
                                </v-btn>
                            </v-layout>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <div v-if="showData.inventarios.length > 0">
            <v-data-table hide-actions :headers="headers" :items="showData.inventarios">
                <template v-slot:items="inventario">
                    <td>{{ inventario.item.cantidad }}</td>
                    <td>{{ inventario.item.lote }}</td>
                    <td class="hidden-xs-only">{{ inventario.item.vencimiento }}</td>
                    <td>{{ inventario.item.proveedor.razonsocial }}</td>
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile @click="editInventario(inventario.item.lote)">
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </template>
            </v-data-table>
        </div>
        <div v-else>
            <br />
            <h2 class="text-xs-center">¡Este producto no tiene inventario!</h2>
            <br />
        </div>
        <v-dialog v-model="preventSaveDialog" width="750" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>{{ this.msg }}</v-card-text>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn
                            :disabled="process"
                            @click="preventSaveDialog = false"
                            outline
                            color="primary"
                        >Cancelar</v-btn>
                        <v-btn
                            :loading="process"
                            :disabled="process"
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
            formPanel: [false],
            headers: [
                { text: "Cantidad", sortable: false },
                { text: "Lote", sortable: false },
                {
                    text: "Vencimiento",
                    sortable: false,
                    class: "hidden-xs-only"
                },
                { text: "Proveedor", sortable: false },
                { text: "", sortable: false }
            ],

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
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["showData", "form"]),

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
        }
    },

    methods: {
        ...mapActions("crudx", ["index", "save", "show"]),

        panelControl() {
            if (this.formPanel[0]) {
                this.active = false;
                this.$refs.inventariosForm.reset();
                this.precio = null;
                this.alicuota = null;
                this.formPanel = [false];
            } else {
                this.active = true;
                this.movimiento = "ALTA";
                this.movimientos = ["ALTA"];
                this.disabledMovimiento = true;
                this.form.costo = this.showData.articulo.costo;
                this.form.utilidades = this.showData.articulo.utilidades;
                if (this.showData.articulo.alicuota == 21) {
                    this.alicuota = 21;
                } else {
                    this.alicuota = 10.5;
                }
                this.precio = this.showData.articulo.precio;
                this.formPanel = [true];
            }
        },

        findLote: async function() {
            if (this.form.lote) {
                if (this.form.lote.length > 0) {
                    this.process = true;
                    let response = await this.index({
                        url: "/api/inventarios",
                        lote: this.form.lote,
                        articulo_id: this.showData.articulo.id
                    });

                    this.process = false;

                    if (response.length > 0) {
                        this.form.lote = response[0].lote;
                        this.form.vencimiento = response[0].vencimiento;
                        this.form.supplier = response[0].supplier.razonsocial;
                        this.form.supplier_id = response[0].supplier.id;
                        this.movimiento = "INCREMENTO";
                        this.movimientos = [
                            "INCREMENTO",
                            "DEVOLUCION",
                            "VENCIMIENTO",
                            "DECREMENTO"
                        ];
                        this.disabledMovimiento = false;
                    } else {
                        this.form.vencimiento = null;
                        this.form.supplier = null;
                        this.form.supplier_id = null;
                        this.movimiento = "ALTA";
                        this.movimientos = ["ALTA"];
                        this.disabledMovimiento = true;
                    }
                }
            }
        },

        findSuppliers: async function() {
            this.form.supplier_id = null;
            this.process = true;
            let response = await this.index({
                url: "/api/suppliers",
                buscarProveedor: this.form.supplier,
                limit: 5
            });
            this.process = false;
            this.suppliers = response.proveedores;
        },

        editInventario: async function(lote) {
            this.form.lote = lote;
            let response = await this.index({
                url: "/api/inventarios",
                lote: this.form.lote,
                articulo_id: this.showData.articulo.id
            });

            if (response.length > 0) {
                this.form.lote = response[0].lote;
                this.form.vencimiento = response[0].vencimiento;
                this.form.supplier = response[0].supplier.razonsocial;
                this.form.supplier_id = response[0].supplier.id;
            } else {
                this.form.vencimiento = null;
                this.form.supplier = null;
                this.form.supplier_id = null;
            }
            this.panelControl();
        },

        selectSupplier(supplier) {
            this.form.supplier = supplier.razonsocial;
            this.form.supplier_id = supplier.id;
            this.suppliers = [];
        },

        preventSave() {
            if (this.$refs.inventariosForm.validate()) {
                this.preventSaveDialog = true;
                if (this.movimiento == "ALTA") {
                    this.msg =
                        "Se agregará el siguiente lote " +
                        this.form.lote +
                        " con stock de " +
                        this.form.cantidad +
                        " productos";
                } else if (this.movimiento == "INCREMENTO") {
                    this.msg =
                        "Se agregarán " +
                        this.form.cantidad +
                        " productos al stock";
                } else {
                    this.msg =
                        "Se restarán " +
                        this.form.cantidad +
                        " productos del stock";
                }
            }
        },

        saveInventarios: async function() {
            if (this.$refs.inventariosForm.validate()) {
                this.process = true;
                this.form.movimiento = this.movimiento;
                this.form.alicuota = this.alicuota;
                this.form.precio = this.precio;
                this.form.articulo_id = this.showData.articulo.id;
                await this.save({ url: "/api/inventarios" });
                this.formPanel = [false];
                this.active = false;
                this.precio = null;
                this.alicuota = null;
                await this.show({
                    url: "/api/articulos/" + this.showData.articulo.id
                });
                this.$refs.inventariosForm.reset();
                this.process = false;
                this.preventSaveDialog = false;
            }
        }
    }
};
</script>

<style>
</style>
