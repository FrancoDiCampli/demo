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
                                <span class="headline">{{articulo.articulo}}</span>
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
                            <tr @click="inventario.expanded = !inventario.expanded">
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

                        <!-- end Movimientos -->
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-flex>
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
            ]
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
        }
    }
};
</script>

<style>
</style>
