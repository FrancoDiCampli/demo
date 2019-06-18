<template>
    <div>
        <template>
            <v-data-table
                hide-actions
                :headers="headers"
                :items="data.cuentas"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="cuenta">
                    <td>{{ cuenta.item.id }}</td>
                    <td>{{ cuenta.item.factura_id }}</td>
                    <td>{{ cuenta.item.importe }}</td>
                    <td>{{ cuenta.item.saldo }}</td>
                    <td>{{ cuenta.item.ultimopago }}</td>

                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title
                                        @click="cuenta_id = cuenta.item.id; pagarCuenta = true;"
                                    >Imprimir</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </template>
            </v-data-table>
            <v-layout justify-center>
                <v-btn
                    :loading="loadingButton"
                    :disabled="limit >= data.total || loadingButton"
                    @click="loadMore()"
                    color="primary"
                    outline
                >Cargar Más</v-btn>
            </v-layout>

            <!-- Dialog Grabar -->

            <v-dialog v-model="pagarCuenta" width="750" persistent>
                <v-card>
                    <v-card-title>
                        <h2>¿Estás Seguro?</h2>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>¿Estás seguro que deseas grabar esta Factura? este cambio es irreversible</v-card-text>
                    <v-card-text>
                        <v-layout justify-end wrap>
                            <v-btn
                                @click="pagarCuenta = false;"
                                outline
                                color="primary"
                                :disabled="process"
                            >Cancelar</v-btn>

                            <v-btn
                                :loading="process"
                                :disabled="process"
                                @click="pagar(cuenta_id)"
                                color="primary"
                            >Pagar</v-btn>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </template>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "CuentaIndex",
    data() {
        return {
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Id", sortable: false },
                { text: "Nº Factura", sortable: false },
                {
                    text: "Importe",
                    sortable: false
                },
                { text: "Saldo", sortable: false },
                { text: "Fecha Ultimo Pago", sortable: false },
                { text: "", sortable: false }
            ],
            pagarCuenta: false,
            cuenta_id: null,
            process: false
        };
    },
    computed: {
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/cuentascorrientes", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({
                url: "api/cuentascorrientes",
                limit: this.limit
            });
            this.loadingButton = false;
        },
        pagar(cuenta_id) {
            axios
                .get("/api/pagartodo/" + this.cuenta_id)
                .then(response => {
                    console.log(response.data);
                    this.index({
                        url: "api/cuentascorrientes",
                        limit: this.limit
                    });
                    this.cuenta_id = null;
                    this.pagarCuenta = false;
                    this.process = false;
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
