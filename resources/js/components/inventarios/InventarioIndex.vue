<template>
    <v-layout wrap justify-center>
        <v-flex xs12 sm10 v-if="articulo!=null">
            <v-card>
                <v-img
                    class="white--text"
                    height="200px"
                    src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                >
                    <v-container fill-height fluid>
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <span class="headline">{{articulo.articulo}} - {{articulo.id}}</span>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-img>
                <v-card-title>
                    <div>
                        <span class="grey--text">
                            <i class="fas fa-dollar-sign"></i>
                            {{articulo.precio}}
                        </span>
                        <br />
                    </div>
                </v-card-title>
                <v-card-actions>
                    <v-btn flat color="orange">Share</v-btn>
                    <v-btn flat color="orange">Explore</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>

        <v-flex xs12 sm10 v-if="articulo!=null">
            <v-card>
                <v-card-title></v-card-title>
                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="inventarios"
                        class="elevation-1"
                        :expand="expand"
                        item-key="id"
                    >
                        <template v-slot:items="inventario">
                            <tr
                                @click="inventario.expanded = !inventario.expanded;detallar(inventario.item.id)"
                            >
                                <td>{{ inventario.item.id }}</td>
                                <td>
                                    <v-edit-dialog
                                        :return-value.sync="inventario.item.cantidad"
                                        lazy
                                    >
                                        {{ inventario.item.cantidad }}
                                        <template v-slot:input>
                                            <v-select
                                                :items="movimiento"
                                                v-model="tmov"
                                                item-text="movimiento"
                                                item-value="movimiento"
                                                label="Outline style"
                                                outline
                                            ></v-select>
                                            <v-text-field
                                                v-model="cantidad"
                                                label="Cantidad"
                                                single-line
                                                @keyup.enter="updateCantidad(inventario.item.id)"
                                            ></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td class="text-xs-right">{{ inventario.item.lote }}</td>
                                <td class="text-xs-right">{{ inventario.item.vencimiento }}</td>
                                <td>
                                    <v-menu>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon dark color="primary" v-on="on">
                                                <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-tile>
                                                <v-list-tile-title>Vencido</v-list-tile-title>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </td>
                            </tr>
                        </template>

                        <!-- Movimientos -->
                        <template v-slot:expand="inventario">
                            <v-card flat>
                                <v-card-text>
                                    <v-data-table
                                        :headers="details"
                                        :items="detalles"
                                        class="elevation-1"
                                    >
                                        <template v-slot:items="detalle">
                                            <td>{{ detalle.item.id }}</td>
                                            <td class="text-xs-right">{{ detalle.item.tipo }}</td>
                                            <td class="text-xs-right">{{ detalle.item.cantidad }}</td>
                                            <td class="text-xs-right">{{ detalle.item.fecha }}</td>
                                            <td
                                                class="text-xs-right"
                                            >{{ detalle.item.numcomprobante }}</td>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </template>
                        <!-- end Movimientos -->
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex>
            <v-dialog v-model="nuevoInventario" persistent max-width="600px">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark v-on="on">Open Dialog</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">User Profile</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field label="Cantidad" required v-model="cantidad"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field
                                        label="Stock Minimo"
                                        hint="example of helper text only on focus"
                                        v-model="stock"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12 sm6 md4>
                                    <v-text-field label="Costo" v-model="preciocosto"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field
                                        v-model="lote"
                                        label="Lote"
                                        hint="coloca un lote para identificar el vencimiento"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12 sm6>
                                    <v-text-field label="Vencimiento" v-model="vto"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-select
                                        :items="suppliers"
                                        v-model="supplier"
                                        item-text="proveedor"
                                        item-value="id"
                                        label="Outline style"
                                        outline
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <small>*indicates required field</small>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="nuevoInventario = false">Close</v-btn>
                        <v-btn
                            color="blue darken-1"
                            flat
                            @click="nuevoInventario = false;guardarInventario() "
                        >Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
        <v-btn dark fab fixed right bottom color="primary" @click="nuevoInventario=true">
            <v-icon>fas fa-plus</v-icon>
        </v-btn>
    </v-layout>
</template>

<script>
import { mapState, mapActions } from "vuex";
import axios from "axios";
export default {
    data() {
        return {
            cantidad: null,
            tmov: null,
            articulo: null,
            inventarios: [],
            expand: false,
            nuevoInventario: false,
            headers: [
                { text: "ID", sortable: false, class: "hidden-xs-only" },
                { text: "Cantidad", sortable: false },
                {
                    text: "Lote",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
                {
                    text: "Vencimiento",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
                { text: "Acciones", sortable: false }
            ],
            movimiento: [
                {
                    movimiento: "VENTA",
                    valor: 1
                },
                { movimiento: "COMPRA", valor: 2 },
                { movimiento: "DEVOLUCION", valor: 3 },
                { movimiento: "VENCIMIENTO", valor: 4 }
            ],
            suppliers: [
                { id: 1, proveedor: "ARCOR" },
                { id: 2, proveedor: "SANCOR" },
                { id: 3, proveedor: "COCA COLA" }
            ],
            details: [
                { text: "Id", sortable: false },
                { text: "Movimiento", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Fecha", sortable: false },
                { text: "No Comprobante", sortable: false }
            ],
            vto: null,
            stock: null,
            cantidad: null,
            supplier: null,
            preciocosto: null,
            lote: null,
            articulo_id: null,
            detalles: []
        };
    },

    computed: {
        ...mapState("crudx", ["showData"])
    },

    mounted() {
        this.getArticle();
        this.getInventario();
    },

    methods: {
        ...mapActions("crudx", ["show", "save"]),
        getArticle: async function() {
            let response = await this.show({ url: "api/articulos/1" });
            this.articulo = response;
        },
        getInventario: async function() {
            let response = await this.show({ url: "api/inventarios/1" });
            this.inventarios = response;
            console.log(this.inventarios);
        },
        updateCantidad($id) {
            let data = {
                id: $id,
                cantidad: this.cantidad,
                movimiento: this.tmov
            };
            axios.post("api/inventario", data).then(response => {
                console.log(response.data);
            });

            this.getInventario();
        },
        guardarInventario() {
            let data = {
                cantidad: this.cantidad,
                stockminimo: this.stock,
                vencimiento: this.vto,
                articulo_id: this.articulo.id,
                supplier_id: this.supplier,
                preciocosto: this.preciocosto,
                lote: this.lote
            };

            axios.post("api/inventarios", data).then(response => {
                console.log(response.data);
            });

            this.getInventario();
        },
        detallar($id) {
            axios
                .get("/api/inventario/" + $id)
                .then(response => {
                    this.detalles = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>

<style>
</style>
