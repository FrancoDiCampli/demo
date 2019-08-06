<template>
    <div>
        <br />
        <v-snackbar color="primary" v-model="snackbar" :timeout="6000" right top>
            Cuenta Actualizada
            <v-btn color="white" flat @click="snackbar = false" icon>
                <v-icon>fas fa-times</v-icon>
            </v-btn>
        </v-snackbar>
        <v-tabs right hide-slider active-class="primary--text">
            <v-layout justify-start mt-2 ml-4>
                <h2>Saldo: {{ saldo }}</h2>
            </v-layout>
            <v-tab>Activas</v-tab>
            <v-tab>Todas</v-tab>
            <v-tab>Recibos</v-tab>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-layout justify-space-center>
                            <v-flex v-show="cuentasActivas.length > 0">
                                <v-data-table
                                    v-model="selected"
                                    hide-actions
                                    :items="cuentasActivas"
                                >
                                    <template v-slot:headers="props">
                                        <tr>
                                            <th>
                                                <v-checkbox
                                                    :input-value="props.all"
                                                    :indeterminate="selected.length > 0 && selected.length < showData.cuentas.length"
                                                    color="primary"
                                                    hide-details
                                                    @click.stop="toggleAll"
                                                ></v-checkbox>
                                            </th>
                                            <th class="hidden-xs-only">Nº Factura</th>
                                            <th v-show="!pagar">Importe</th>
                                            <th>Saldo</th>
                                            <th v-show="!pagar" class="hidden-sm-and-down">Alta</th>
                                            <th class="hidden-sm-and-down">Último Pago</th>
                                            <th v-show="pagar">Monto a pagar</th>
                                        </tr>
                                    </template>
                                    <template v-slot:items="cuenta">
                                        <tr class="text-xs-center">
                                            <td
                                                @click="
                                    cuenta.selected = !cuenta.selected;
                                    if(!cuenta.selected) {
                                        cuenta.item.value = cuenta.item.saldo;
                                    } else {
                                        cuenta.item.value = null;
                                    }
                                    
                                "
                                            >
                                                <v-checkbox
                                                    :input-value="cuenta.selected"
                                                    color="primary"
                                                    hide-details
                                                ></v-checkbox>
                                            </td>
                                            <td
                                                class="hidden-xs-only"
                                            >{{ cuenta.item.factura.numfactura }}</td>
                                            <td v-show="!pagar">{{ cuenta.item.importe }}</td>
                                            <td>{{ cuenta.item.saldo }}</td>
                                            <td
                                                v-show="!pagar"
                                                class="hidden-sm-and-down"
                                            >{{ cuenta.item.alta }}</td>
                                            <td>
                                                <div v-if="cuenta.item.diferencia >= 30">
                                                    <v-menu
                                                        v-model="cuenta.item.menuDiff"
                                                        :close-on-content-click="false"
                                                        :nudge-width="200"
                                                        max-width="350px"
                                                        left
                                                        offset-x
                                                    >
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn
                                                                color="error"
                                                                flat
                                                                icon
                                                                v-on="on"
                                                                @click="setRecargo(cuenta.item.saldo)"
                                                            >
                                                                <v-icon>fas fa-exclamation-circle</v-icon>
                                                            </v-btn>
                                                        </template>

                                                        <v-card>
                                                            <v-list>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title
                                                                            class="text-xs-center error--text"
                                                                        >Esta cuenta esta vencida hace {{ cuenta.item.diferencia }} días</v-list-tile-title>
                                                                        <v-list-tile-sub-title
                                                                            class="text-xs-center"
                                                                        >Si lo desea, establezca el porcentaje a recargar</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                            </v-list>

                                                            <v-divider></v-divider>

                                                            <v-layout justify-center wrap>
                                                                <v-flex xs10 px-2 mt-3>
                                                                    <v-text-field
                                                                        v-model="recargo"
                                                                        label="Recargo %"
                                                                        box
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs10 px-2>
                                                                    <v-text-field
                                                                        v-model="nuevoSaldo"
                                                                        disabled
                                                                        label="Saldo"
                                                                        box
                                                                    ></v-text-field>
                                                                </v-flex>
                                                            </v-layout>

                                                            <v-card-actions>
                                                                <v-layout justify-center mb-2>
                                                                    <v-btn
                                                                        color="primary"
                                                                        outline
                                                                        @click="cuenta.item.menuDiff = false; resetRecargo()"
                                                                    >Cancelar</v-btn>
                                                                    <v-btn
                                                                        :disabled="recargo != null && recargo != '' ? false : true"
                                                                        color="primary"
                                                                        @click="recargar(cuenta.item.id); cuenta.item.menuDiff = false"
                                                                    >Guardar</v-btn>
                                                                </v-layout>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-menu>
                                                </div>
                                                <div>{{ cuenta.item.ultimopago }}</div>
                                            </td>
                                            <td v-show="pagar && cuenta.selected">
                                                <v-layout justify-center>
                                                    <input
                                                        v-model="cuenta.item.value"
                                                        type="text"
                                                        class="input-pagos"
                                                    />
                                                </v-layout>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-flex>
                            <v-flex v-show="cuentasActivas.length <= 0">
                                <v-layout justify-center>
                                    <h2>El cliente no tiene cuentas activas</h2>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                        <br />
                        <v-layout justify-center v-show="pagar">
                            <v-btn
                                :disabled="loadingButton"
                                @click="verificarPago()"
                                color="primary"
                            >Pagar</v-btn>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-layout justify-space-between>
                            <v-flex>
                                <v-data-table
                                    :expand="false"
                                    item-key="id"
                                    hide-actions
                                    :headers="headersCuentas"
                                    :items="showData.cuentas"
                                >
                                    <template v-slot:headers="props">
                                        <tr>
                                            <th
                                                v-for="header in props.headers"
                                                :key="header.text"
                                                :class="header.class"
                                            >{{ header.text }}</th>
                                        </tr>
                                    </template>
                                    <template v-slot:items="cuenta">
                                        <tr class="text-xs-center">
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                                class="hidden-xs-only"
                                            >{{ cuenta.item.factura.numfactura }}</td>
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                            >{{ cuenta.item.importe }}</td>
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                            >{{ cuenta.item.saldo }}</td>
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                                class="hidden-sm-and-down"
                                            >{{ cuenta.item.alta }}</td>
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                                class="hidden-sm-and-down"
                                            >{{ cuenta.item.ultimopago }}</td>
                                            <td
                                                @click="cuenta.expanded = !cuenta.expanded"
                                                style="cursor: pointer;"
                                            >{{ cuenta.item.estado }}</td>
                                        </tr>
                                    </template>
                                    <template v-slot:expand="cuenta">
                                        <v-card flat>
                                            <v-card-text>
                                                <v-list two-line>
                                                    <template>
                                                        <v-subheader>Movimientos</v-subheader>
                                                        <div
                                                            v-for="movimiento in cuenta.item.movimientos"
                                                            :key="movimiento.id"
                                                        >
                                                            <v-divider></v-divider>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>
                                                                        <p>
                                                                            <b>{{ movimiento.fecha }}</b>
                                                                        </p>
                                                                    </v-list-tile-title>
                                                                    <v-list-tile-sub-title>
                                                                        <v-layout
                                                                            justify-space-between
                                                                        >
                                                                            <v-flex
                                                                                xs6
                                                                                class="text-xs-left"
                                                                            >{{ movimiento.tipo }}</v-flex>
                                                                            <v-flex
                                                                                xs6
                                                                                class="text-xs-right"
                                                                            >${{ movimiento.importe }}</v-flex>
                                                                        </v-layout>
                                                                    </v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </div>
                                                    </template>
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-layout justify-space-between>
                            <v-flex v-show="showData.recibos.length > 0">
                                <v-data-table
                                    :expand="false"
                                    item-key="id"
                                    hide-actions
                                    :items="showData.recibos"
                                >
                                    <template v-slot:headers="props">
                                        <tr>
                                            <th>Nº Recibo</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th></th>
                                        </tr>
                                    </template>
                                    <template v-slot:items="recibo">
                                        <tr>
                                            <td>{{recibo.item.numrecibo}}</td>
                                            <td>{{recibo.item.fecha}}</td>
                                            <td>{{recibo.item.total}}</td>
                                            <td>
                                                <v-menu>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn
                                                            flat
                                                            icon
                                                            dark
                                                            color="primary"
                                                            v-on="on"
                                                        >
                                                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <v-list>
                                                        <v-list-tile
                                                            @click="StreamrecibosPDF(recibo.item.id)"
                                                        >
                                                            <v-list-tile-title>Imprimir</v-list-tile-title>
                                                        </v-list-tile>
                                                    </v-list>
                                                </v-menu>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>
        <!-- modal grabar factura -->
        <v-dialog v-model="pagarCuentasDialog" width="750" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>¿Estás seguro que deseas realizar el pago de ${{pagoTotal}}? este cambio es irreversible</v-card-text>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn
                            @click="cancelarPago()"
                            outline
                            color="primary"
                            :disabled="loadingButton"
                        >Cancelar</v-btn>

                        <v-btn
                            :loading="loadingButton"
                            :disabled="loadingButton"
                            @click="pagarCuentas()"
                            color="primary"
                        >Pagar</v-btn>
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
import { mapState, mapActions } from "vuex";

export default {
    name: "ClientesShowCuentaTable",

    data() {
        return {
            headersCuentas: [
                {
                    text: "Nº Factura",
                    sortable: false,
                    class: "hidden-xs-only"
                },
                {
                    text: "Importe",
                    sortable: false
                },
                { text: "Debe", sortable: false },
                { text: "Alta", sortable: false, class: "hidden-sm-and-down" },
                {
                    text: "Ultimo Pago",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
                { text: "Estado", sortable: false }
            ],
            selected: [],
            state: true,
            pagarCuentasDialog: false,
            snackbar: false,
            pagoTotal: null,
            loadingButton: false,
            recargo: null,
            saldoActual: null
        };
    },

    computed: {
        ...mapState("crudx", ["showData", "form"]),

        nuevoSaldo: {
            set() {},
            get() {
                if (
                    this.recargo != null &&
                    this.recargo != "" &&
                    this.saldoActual != null
                ) {
                    let saldo = Number(this.saldoActual);
                    let value = Number((saldo * this.recargo) / 100);
                    saldo += value;
                    return saldo.toFixed(2);
                } else {
                    return null;
                }
            }
        },

        saldo: {
            set() {},
            get() {
                if (this.showData.cuentas.length > 0) {
                    let saldo = 0;
                    for (let i = 0; i < this.showData.cuentas.length; i++) {
                        saldo += this.showData.cuentas[i].saldo * 1;
                    }
                    return saldo.toFixed(2);
                } else {
                    return null;
                }
            }
        },

        pagar() {
            if (this.selected.length > 0) {
                return true;
            } else {
                return false;
            }
        },

        cuentasActivas() {
            let arrayCuentasActivas = [];
            if (this.showData.cuentas.length > 0) {
                for (let i = 0; i < this.showData.cuentas.length; i++) {
                    if (this.showData.cuentas[i].estado == "ACTIVA") {
                        arrayCuentasActivas.push(this.showData.cuentas[i]);
                    }
                }
            }

            return arrayCuentasActivas;
        }
    },

    methods: {
        ...mapActions("crudx", ["show", "save"]),
        toggleAll() {
            if (this.selected.length) {
                for (let i = 0; i < this.selected.length; i++) {
                    this.selected[i].value = null;
                }
                this.selected = [];
            } else {
                for (let i = 0; i < this.showData.cuentas.length; i++) {
                    if (this.showData.cuentas[i].saldo > 0) {
                        this.selected.push(this.showData.cuentas[i]);
                    }
                }

                for (let i = 0; i < this.selected.length; i++) {
                    this.selected[i].value = this.selected[i].saldo;
                }
            }
        },

        verificarPago() {
            if (this.selected.length > 0) {
                for (let i = 0; i < this.selected.length; i++) {
                    this.pagoTotal += Number(this.selected[i].value);
                    this.pagoTotal = this.pagoTotal.toFixed(2);
                }
                this.pagarCuentasDialog = true;
            }
        },

        cancelarPago() {
            this.selected = [];
            this.pagoTotal = null;
            this.pagarCuentasDialog = false;
        },

        recibosPDF: function(id) {
            axios({
                url: "/api/recibosPDF/" + id,
                method: "GET",
                responseType: "blob"
            }).then(response => {
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "recibo" + id + ".pdf");
                document.body.appendChild(link);
                link.click();
            });
        },

        StreamrecibosPDF(id) {
            window.open("/api/recibosPDF/" + id);
        },

        pagarCuentas: async function() {
            if (this.selected.length > 0) {
                this.loadingButton = true;
                this.form.pago = this.selected;
                var reciboID = await this.save({ url: "/api/pagarcuentas" });
                this.recibosPDF(reciboID);
                await this.show({
                    url: "/api/clientes/" + this.showData.cliente.id
                });
                this.selected = [];
                this.loadingButton = false;
                this.pagarCuentasDialog = false;
                this.snackbar = true;
                this.pagoTotal = null;
            }
        },

        setRecargo(saldo) {
            this.saldoActual = saldo;
        },

        resetRecargo() {
            this.recargo = null;
            this.saldoActual = null;
        },

        recargar(id) {
            if (this.recargo != null && this.recargo != "") {
                let formulario = {
                    id: Number(id),
                    nuevoSaldo: Number(this.nuevoSaldo)
                };

                axios
                    .post("/api/recargar", formulario)
                    .then(response => {
                        this.show({
                            url: "/api/clientes/" + this.showData.cliente.id
                        });
                        this.resetRecargo();
                        this.snackbar = true;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.resetRecargo();
            }
        }
    }
};
</script>

<style>
</style>
