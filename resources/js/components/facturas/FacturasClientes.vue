<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    @keyup="findClient()"
                    v-model="client"
                    label="Cliente"
                    box
                    single-line
                ></v-text-field>
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
                                @click="selected = client.item.id"
                                @dblclick="selectClient(client.item)"
                                style="cursor: pointer;"
                                :style="selected == client.item.id ? 'background-color: #26A69A; color: #FFFFFF;' : ''"
                            >
                                <td>{{ client.item.documentounico }}</td>
                                <td>{{ client.item.razonsocial }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>
            <v-flex xs12 sm5>
                <v-select
                    v-model="form.condicion"
                    :items="terms"
                    label="Condición"
                    hint="Condición"
                    box
                    single-line
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout v-if="detailClient.cliente">
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
        </v-layout>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState } from "vuex";

export default {
    name: "FacturasClientes",

    data() {
        return {
            client: null,
            detailClient: [],
            customers: [],
            selected: null,
            terms: ["Contado", "Credito / Debito", "Cuenta Corriente"]
        };
    },

    computed: {
        ...mapState("crudx", ["form"])
    },

    methods: {
        findClient() {
            this.selected = null;
            this.detailClient = [];
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
        },

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

<style>
.search-table {
    border: solid 2px #26a69a;
    margin-top: -30px;
    border-top: none;
    margin-bottom: 20px;
    border-radius: 0px 0px 5px 5px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter {
    transform: translateY(-60px);
}

.fade-leave-to {
    opacity: 0;
}

.expansion-border {
    border-bottom: 1px solid #aaaaaa;
}
</style>
