<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm4 mx-1>
                <v-text-field
                    v-model="form.cuit"
                    label="Cuit"
                    hint="Cuit"
                    box
                    single-line
                    @keyup.40="find()"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-text-field
                    v-model="form.razonsocial"
                    disabled
                    label="Razón Social"
                    hint="Razón Social"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-select
                    :items="condiciones"
                    v-model="condicion"
                    disabled
                    box
                    single-line
                    label="Condición"
                    hint="Condición"
                ></v-select>
            </v-flex>
        </v-layout>

        <v-layout justify-space-around>
            <v-flex xs12 sm6 mx-1>
                <v-text-field
                    v-model="form.codarticulo"
                    @keyup="findArticulos()"
                    label="Codigo Articulo"
                    hint="Codigo Articulo"
                    box
                    single-line
                    color="primary"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 mx-1>
                <v-text-field
                    v-model="form.articulo"
                    @keyup="findArticulos()"
                    label="Articulo"
                    hint="Articulo"
                    box
                    single-line
                    color="primary"
                ></v-text-field>
            </v-flex>
        </v-layout>

        <div v-if="articulos != null">
            <v-data-table :items="articulos" hide-actions hide-headers>
                <template v-slot:items="articulo">
                    <tr
                        @click="selected = articulo.item.id"
                        @dblclick="loadArticulos(articulo.item)"
                        style="cursor: pointer;"
                        :style="selected == articulo.item.id ?
                        'background-color: #26A69A; color: white;' : ''"
                    >
                        <td class="hidden-xs-only">{{ articulo.item.codarticulo }}</td>
                        <td>{{ articulo.item.articulo }}</td>
                    </tr>
                </template>
            </v-data-table>
        </div>

        <v-layout>
            <v-flex xs12 sm4 mx-1>
                <v-text-field
                    v-model="form.cantidad"
                    label="Cantidad"
                    hint="Cantidad"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-text-field
                    v-model="form.precio"
                    disabled
                    label="Precio"
                    hint="Precio"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 mx-1>
                <v-text-field
                    v-model="subtotal"
                    disabled
                    label="Subtotal"
                    hint="Subtotal"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>

        <v-dialog v-model="findClienteDialog" width="750" persistent>
            <v-card>
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>Buscar Cliente</h2>
                        <!-- Boton cerrar modal -->
                        <v-btn
                            @click="FindClientesDialog()"
                            flat
                            icon
                            style="margin: 0; padding: 0;"
                        >
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <FindCliente></FindCliente>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations } from "vuex";

//Components
import FindCliente from "./FindCliente.vue";

export default {
    name: "FacturasForm",

    data() {
        return {
            condiciones: ["Contado", "Credito - Debito", "Cuenta Corriente"],
            condicion: "Contado",
            articulos: null,
            selected: null
        };
    },

    components: {
        FindCliente
    },

    computed: {
        ...mapState(["findClienteDialog"]),
        ...mapState("crudx", ["form"]),

        subtotal() {
            return this.form.precio * this.form.cantidad;
        }
    },

    methods: {
        ...mapMutations(["FindClientesDialog"]),
        find() {
            this.FindClientesDialog();
        },

        findArticulos() {
            this.selected = null;
            if (
                (this.form.codarticulo != null &&
                    this.form.codarticulo != "") ||
                (this.form.articulo != null && this.form.articulo != "")
            ) {
                axios
                    .get("/api/articulos", {
                        params: {
                            codarticulo: this.form.codarticulo,
                            articulo: this.form.articulo
                        }
                    })
                    .then(response => {
                        this.articulos = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
            } else {
                this.articulos = null;
            }
        },

        loadArticulos(articulo) {
            this.form.codarticulo = articulo.codarticulo;
            this.form.articulo = articulo.articulo;
            this.form.precio = articulo.precio;
            this.articulos = null;
            this.selected = null;
        }
    }
};
</script>

<style>
</style>
