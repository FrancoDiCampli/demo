<template>
    <div>
        <br>
        <v-layout justify-center>
            <h2>Saldo: {{ saldo }}</h2>
        </v-layout>
        <br>
        <v-tabs right hide-slider active-class="primary--text">
            <v-tab>Activas</v-tab>
            <v-tab>Todas</v-tab>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-layout justify-space-between>
                            <v-flex>
                                <v-data-table
                                    v-model="selected"
                                    hide-actions
                                    :items="showData.cuentas"
                                >
                                    <template v-slot:headers="props">
                                        <tr>
                                            <th>
                                                <v-checkbox
                                                    :input-value="props.all"
                                                    :indeterminate="selected.length > 0 && selected.length < showData.cuentas.length"
                                                    primary
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
                                        <tr
                                            class="text-xs-center"
                                            v-show="cuenta.item.estado == 'ACTIVA'"
                                        >
                                            <td
                                                @click="if(cuenta.item.saldo > 0){
                                    cuenta.selected = !cuenta.selected;
                                    cuenta.item.value = cuenta.item.saldo;
                                }"
                                            >
                                                <v-checkbox
                                                    :input-value="cuenta.selected"
                                                    :disabled="cuenta.item.saldo <= 0"
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
                                            <td
                                                class="hidden-sm-and-down"
                                            >{{ cuenta.item.ultimopago }}</td>
                                            <td v-show="pagar">
                                                <v-layout justify-center>
                                                    <input
                                                        v-model="cuenta.item.value"
                                                        :disabled="cuenta.item.saldo <= 0"
                                                        type="text"
                                                        class="input-pagos"
                                                    >
                                                </v-layout>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                        <br>
                        <v-layout justify-center v-show="pagar">
                            <v-btn color="primary">Pagar</v-btn>
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
                                                class="hidden-xs-only"
                                            >{{ cuenta.item.factura.numfactura }}</td>
                                            <td>{{ cuenta.item.importe }}</td>
                                            <td>{{ cuenta.item.saldo }}</td>
                                            <td class="hidden-sm-and-down">{{ cuenta.item.alta }}</td>
                                            <td
                                                class="hidden-sm-and-down"
                                            >{{ cuenta.item.ultimopago }}</td>
                                            <td>{{ cuenta.item.estado }}</td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
import { mapState } from "vuex";
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
            state: true
        };
    },

    computed: {
        ...mapState("crudx", ["showData"]),

        saldo: {
            set() {},
            get() {
                if (this.showData.cuentas.length > 0) {
                    let saldo = 0;
                    for (let i = 0; i < this.showData.cuentas.length; i++) {
                        saldo += this.showData.cuentas[i].saldo * 1;
                    }
                    return saldo;
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
        }
    },

    methods: {
        toggleAll() {
            if (this.selected.length) {
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
        }
    }
};
</script>

<style>
.input-pagos {
    display: block;
    margin-top: 8px;
    padding: 10px 0px;
    border: none;
    border-bottom: 1px solid #9e9e9e;
    transition: all 1s ease;
}

.input-pagos:focus {
    outline: none;
    border-bottom: 2px solid #26a69a;
    transition: all 0.5s ease;
}

.pagos tbody tr {
    border-bottom: none !important;
}
</style>
