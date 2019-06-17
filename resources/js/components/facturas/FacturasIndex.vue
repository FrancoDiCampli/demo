<template>
    <div>
        <!-- Facturas Table -->
        <template>
            <v-data-table
                hide-actions
                :headers="headers"
                :items="data.facturas"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="factura">
                    <td>
                        <v-avatar class="type-item">
                            <div v-if="factura.item.cae == null">
                                <p class="title type">X</p>
                            </div>
                            <div v-else>
                                <p class="title type">C</p>
                            </div>
                        </v-avatar>
                    </td>
                    <td>
                        <div
                            v-if="factura.item.comprobanteafip != null"
                        >{{ factura.item.comprobanteafip }}</div>
                        <div v-else>{{ factura.item.id }}</div>
                    </td>
                    <td class="hidden-sm-and-down">{{ factura.item.cuit }}</td>
                    <td>{{ factura.item.total }}</td>
                    <td>{{ factura.item.fecha }}</td>
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title>Imprimir</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile
                                    v-show="factura.item.cae == null"
                                    @click="factura_id = factura.item.id; grabarFacturasDialog = true;"
                                >
                                    <v-list-tile-title>Grabar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile v-show="factura.item.cae == null">
                                    <v-list-tile-title>Anular</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                    <td></td>
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
            <v-dialog v-model="grabarFacturasDialog" width="750" persistent>
                <v-card>
                    <v-card-title>
                        <h2>¿Estás Seguro?</h2>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>¿Estás seguro que deseas grabar esta Factura? este cambio es irreversible</v-card-text>
                    <v-card-text>
                        <v-layout justify-end wrap>
                            <v-btn
                                @click="grabarFacturasDialog = false;"
                                outline
                                color="primary"
                                :disabled="process"
                            >Cancelar</v-btn>

                            <v-btn
                                :loading="process"
                                :disabled="process"
                                @click="grabarFactura()"
                                color="primary"
                            >Grabar</v-btn>
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
    name: "ClientesIndex",

    data() {
        return {
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Tipo", sortable: false },
                { text: "Nº Factura", sortable: false },
                { text: "CUIT", sortable: false, class: "hidden-sm-and-down" },
                { text: "Importe", sortable: false },
                { text: "Fecha", sortable: false },
                { text: "", sortable: false }
            ],
            grabarFacturasDialog: false,
            factura_id: null,
            process: false
        };
    },

    computed: {
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/facturas", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "api/facturas", limit: this.limit });
            this.loadingButton = false;
        },

        grabarFactura() {
            this.process = true;
            axios
                .get("/api/solicitarCae/" + this.factura_id)
                .then(response => {
                    console.log(response.data);
                    this.index({ url: "api/facturas", limit: this.limit });
                    this.factura_id = null;
                    this.grabarFacturasDialog = false;
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
.type-item {
    margin: 5px 0px 5px -12px;
    border: solid 1.5px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.type {
    margin-top: 15px;
    color: #26a69a;
}
</style>