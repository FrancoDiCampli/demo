<template>
    <div>
        <!-- Tabla de Facturas -->
        <v-card>
            <v-card-text>
                <template>
                    <v-data-table
                        hide-actions
                        no-data-text="No existe ninguna factura registrada"
                        :headers="headers"
                        :items="data.facturas"
                        :loading="inProcess"
                    >
                        <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                        <template v-slot:items="factura">
                            <td class="hidden-xs-only">
                                <v-avatar class="type-item">
                                    <p class="title type">{{ factura.item.letracomprobante }}</p>
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
                            <td class="hidden-xs-only">{{ factura.item.fecha }}</td>
                            <!-- menu acciones de facturas -->
                            <td>
                                <v-menu>
                                    <template v-slot:activator="{ on }">
                                        <v-btn flat icon dark color="primary" v-on="on">
                                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <!-- imprimir factura -->
                                        <v-list-tile
                                            v-show="factura.item.cae == null"
                                            @click="remitosPDF(factura.item.id)"
                                        >
                                            <v-list-tile-title>Imprimir</v-list-tile-title>
                                        </v-list-tile>
                                        <v-list-tile
                                            :disabled="process"
                                            v-show="factura.item.cae != null"
                                            @click="facturaPDF(factura.item.id)"
                                        >
                                            <v-list-tile-title>Imprimir</v-list-tile-title>
                                        </v-list-tile>
                                        <!-- grabar factura en afip solo si es X -->
                                        <v-list-tile
                                            :disabled="process"
                                            v-show="factura.item.cae == null && factura.item.pagada == true"
                                            @click="factura_id = factura.item.id; grabarFacturasDialog = true;"
                                        >
                                            <v-list-tile-title>Grabar</v-list-tile-title>
                                        </v-list-tile>
                                        <!-- anular factura solo si es X -->
                                        <v-list-tile
                                            :disabled="process"
                                            v-show="factura.item.cae == null && factura.item.pagada == true"
                                            @click="factura_id = factura.item.id; anularFacturaDialog = true;"
                                        >
                                            <v-list-tile-title>Anular</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </td>
                        </template>
                    </v-data-table>
                    <v-layout justify-center>
                        <v-btn
                            :loading="process"
                            :disabled="limit >= data.total || process"
                            @click="loadMore()"
                            color="primary"
                            outline
                        >Cargar Más</v-btn>
                    </v-layout>

                    <!-- modal grabar factura -->
                    <v-dialog v-model="grabarFacturasDialog" width="750" persistent>
                        <v-card>
                            <v-card-title>
                                <h2>¿Estás Seguro?</h2>
                            </v-card-title>
                            <v-divider></v-divider>
                            <div v-show="process">
                                <v-card-text>
                                    <v-layout justify-center>
                                        <v-progress-circular
                                            :size="70"
                                            :width="7"
                                            color="primary"
                                            indeterminate
                                        ></v-progress-circular>
                                    </v-layout>
                                </v-card-text>
                            </div>
                            <div v-show="!process">
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
                                            :disabled="process"
                                            @click="grabarFactura()"
                                            color="primary"
                                        >Grabar</v-btn>
                                    </v-layout>
                                </v-card-text>
                            </div>
                        </v-card>
                    </v-dialog>

                    <!-- modal anular factura -->
                    <v-dialog v-model="anularFacturaDialog" width="750" persistent>
                        <v-card>
                            <v-card-title>
                                <h2>¿Estás Seguro?</h2>
                            </v-card-title>
                            <v-divider></v-divider>
                            <div v-show="process">
                                <v-card-text>
                                    <v-layout justify-center>
                                        <v-progress-circular
                                            :size="70"
                                            :width="7"
                                            color="primary"
                                            indeterminate
                                        ></v-progress-circular>
                                    </v-layout>
                                </v-card-text>
                            </div>
                            <div v-show="!process">
                                <v-card-text>¿Estás seguro que deseas anular esta Factura? este cambio es irreversible</v-card-text>
                                <v-card-text>
                                    <v-layout justify-end wrap>
                                        <v-btn
                                            @click="anularFacturaDialog = false;"
                                            outline
                                            color="primary"
                                            :disabled="process"
                                        >Cancelar</v-btn>

                                        <v-btn
                                            :disabled="process"
                                            @click="anularFactura()"
                                            color="primary"
                                        >Anular</v-btn>
                                    </v-layout>
                                </v-card-text>
                            </div>
                        </v-card>
                    </v-dialog>
                </template>
            </v-card-text>
        </v-card>
        <br />
        <div v-if="rol == 'superAdmin'">
            <v-card>
                <v-card-title>
                    <h2>Facturas Anuladas</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <template>
                        <v-data-table
                            hide-actions
                            no-data-text="No existe ninguna factura anulada"
                            :headers="headers"
                            :items="data.eliminadas"
                        >
                            <template v-slot:items="eliminada">
                                <td class="hidden-xs-only">
                                    <v-avatar class="type-item">
                                        <p class="title type">{{ eliminada.item.letracomprobante }}</p>
                                    </v-avatar>
                                </td>
                                <td>
                                    <div
                                        v-if="eliminada.item.comprobanteafip != null"
                                    >{{ eliminada.item.comprobanteafip }}</div>
                                    <div v-else>{{ eliminada.item.id }}</div>
                                </td>
                                <td class="hidden-sm-and-down">{{ eliminada.item.cuit }}</td>
                                <td>{{ eliminada.item.total }}</td>
                                <td class="hidden-xs-only">{{ eliminada.item.fecha }}</td>
                            </template>
                        </v-data-table>
                    </template>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
    name: "FacturasIndex",

    data() {
        return {
            facturas: [],
            limit: 10,
            headers: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Factura", sortable: false },
                { text: "CUIT", sortable: false, class: "hidden-sm-and-down" },
                { text: "Importe", sortable: false },
                { text: "Fecha", sortable: false, class: "hidden-xs-only" },
                { text: "", sortable: false }
            ],
            grabarFacturasDialog: false,
            anularFacturaDialog: false,
            factura_id: null,
            process: false
        };
    },

    computed: {
        ...mapState("crudx", ["inProcess", "data"]),
        ...mapState("auth", ["rol"])
    },

    mounted() {
        this.index({ url: "/api/facturas", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index", "destroy"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.process = true;
            await this.index({ url: "/api/facturas", limit: this.limit });
            this.process = false;
        },

        grabarFactura() {
            this.process = true;
            axios
                .get("/api/solicitarCae/" + this.factura_id)
                .then(response => {
                    this.index({ url: "/api/facturas", limit: this.limit });
                    this.factura_id = null;
                    this.grabarFacturasDialog = false;
                    this.process = false;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        anularFactura: async function() {
            this.process = true;
            await this.destroy({ url: "/api/facturas/" + this.factura_id });
            this.anularFacturaDialog = false;
            await this.index({ url: "/api/facturas", limit: this.limit });
            this.process = false;
        },

        facturaPDF: function(id) {
            axios({
                url: "/api/facturasPDF/" + id,
                method: "GET",
                responseType: "blob"
            }).then(response => {
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "factura" + id + ".pdf");
                document.body.appendChild(link);
                link.click();
            });
        },

        remitosPDF: function(id) {
            axios({
                url: "/api/remitosPDF/" + id,
                method: "GET",
                responseType: "blob"
            }).then(response => {
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "remito" + id + ".pdf");
                document.body.appendChild(link);
                link.click();
            });
        }
    }
};
</script>
