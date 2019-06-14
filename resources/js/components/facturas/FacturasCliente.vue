<template>
    <div>
        <!-- Formulario -->
        <v-layout justify-space-around wrap>
            <v-flex xs11 sm5>
                <!-- Input Clientes -->
                <v-text-field
                    @keyup="findClient()"
                    v-model="client"
                    :rules="[rules.required]"
                    label="Cliente"
                    box
                    single-line
                ></v-text-field>

                <!-- Tabla Clientes -->
                <transition name="fade">
                    <v-data-table
                        v-show="client != null && client != '' && customers.length > 0"
                        no-data-text="El cliente no se encuentra en la base de datos."
                        hide-actions
                        hide-headers
                        :items="customers"
                        class="search-table"
                    >
                        <template v-slot:items="client">
                            <tr
                                @click="clientSelected = client.item.id"
                                @dblclick="selectClient(client.item)"
                                style="cursor: pointer;"
                                :style="clientSelected == client.item.id ? 'background-color: #26A69A; color: #FFFFFF;' : ''"
                            >
                                <td>{{ client.item.documentounico }}</td>
                                <td>{{ client.item.razonsocial }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>

            <!-- Input Condición de Venta -->
            <v-flex xs11 sm5>
                <v-select
                    v-model="form.condicion"
                    :items="terms"
                    :rules="[rules.required]"
                    label="Condición"
                    hint="Condición"
                    box
                    single-line
                ></v-select>
            </v-flex>
        </v-layout>
        <!-- Detalles Clientes -->
        <v-layout v-if="detailClient.cliente" justify-space-around>
            <v-flex xs11>
                <template>
                    <v-expansion-panel class="elevation-0 expansion-border">
                        <v-expansion-panel-content>
                            <template v-slot:header>
                                <div>Más Detalles</div>
                            </template>
                            <v-card-text>
                                <p>
                                    <b>CUIT:</b>
                                    {{detailClient.cliente.documentounico}}
                                </p>
                                <p>
                                    <b>Razón Social:</b>
                                    {{detailClient.cliente.razonsocial}}
                                </p>
                                <p>
                                    <b>Condición Frente al IVA:</b>
                                    {{detailClient.cliente.condicioniva}}
                                </p>
                                <p>
                                    <b>Domicilio:</b>
                                    {{detailClient.cliente.direccion}}
                                </p>
                            </v-card-text>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </template>
                <br>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState } from "vuex";

export default {
    name: "FacturasCliente",

    data() {
        return {
            client: "CONSUMIDOR FINAL",
            detailClient: [],
            customers: [],
            clientSelected: null,
            terms: ["CONTADO", "CREDITO / DEBITO", "CUENTA CORRIENTE"],
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"])
    },

    mounted() {
        this.form.cliente_id = 1;
    },

    methods: {
        // Buscar los clientes
        findClient() {
            this.clientSelected = null;
            this.detailClient = [];
            if (this.client == "0") {
                this.customers = [];
                this.detailClient = [];
                this.client = "CONSUMIDOR FINAL";
                this.form.cliente_id = 0;
            } else {
                axios
                    .get("/api/clientes", {
                        params: {
                            buscarCliente: this.client,
                            limit: 5
                        }
                    })
                    .then(response => {
                        this.customers = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        // Seleccionar un Cliente
        selectClient(client) {
            this.customers = [];
            this.detailClient = [];
            this.client = client.razonsocial;
            this.form.cliente_id = client.id;

            axios
                .get("/api/clientes/" + client.id)
                .then(response => {
                    this.detailClient = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>