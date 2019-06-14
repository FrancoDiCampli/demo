<template>
    <div>
        <v-card-title>
            <v-layout justify-space-around wrap>
                <v-flex xs12 sm5 mx-1>
                    <h1 class="text-xs-center text-sm-left">Nueva Factura</h1>
                </v-flex>
                <v-flex xs12 sm5 mx-1 class="data text-xs-center text-sm-right">
                    <p>
                        <b>Punto de Venta:</b> 0003
                    </p>
                    <p>
                        <b>Comprobante Nº:</b> 2
                    </p>
                </v-flex>
            </v-layout>
        </v-card-title>
        <v-divider></v-divider>
        <br>
        <v-form ref="formFactura" @submit.prevent="saveFactura">
            <FacturasCliente></FacturasCliente>
            <FacturasArticulo></FacturasArticulo>
            <br>
            <v-layout justify-space-around wrap>
                <v-flex xs12 sm5 mx-1>
                    <v-layout justify-space-around wrap>
                        <v-flex xs11>
                            <v-text-field
                                v-model="form.bonificacion"
                                label="Bonificacion"
                                hint="Bonificacion"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs11>
                            <v-text-field
                                v-model="form.recargo"
                                label="Recargo"
                                hint="Recargo"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs11>
                            <v-select
                                v-model="form.tipo"
                                :items="types"
                                :rules="[rules.required]"
                                label="Tipo Comprobante"
                                hint="Tipo Comprobante"
                                box
                                single-line
                            ></v-select>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs12 sm5 mx-1>
                    <v-layout justify-space-around wrap>
                        <v-flex xs11>
                            <v-text-field
                                v-model="subtotalFactura"
                                disabled
                                :rules="[rules.required]"
                                label="Subtotal"
                                hint="Subtotal"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs11>
                            <v-text-field
                                v-model="total"
                                disabled
                                :rules="[rules.required]"
                                label="Total"
                                hint="Total"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs11>
                            <v-layout justify-center>
                                <v-btn
                                    @click="cancelFactura()"
                                    outline
                                    large
                                    color="primary"
                                >Cancelar</v-btn>
                                <v-btn type="submit" large color="primary">Guardar</v-btn>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-form>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapState, mapActions, mapMutations } from "vuex";

//Components
import FacturasCliente from "./FacturasCliente.vue";
import FacturasArticulo from "./FacturasArticulo.vue";

export default {
    name: "FacturasForm",

    data() {
        return {
            types: ["REMITO X", "FACTURA C"],
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    components: {
        FacturasCliente,
        FacturasArticulo
    },

    computed: {
        ...mapState("crudx", ["form"]),

        subtotal: {
            get() {
                return this.$store.state.subtotal;
            }
        },

        subtotalFactura() {
            if (this.subtotal != null) {
                return this.subtotal.sub;
            } else {
                return null;
            }
        },

        total() {
            if (this.subtotalFactura != null) {
                if (this.form.bonificacion || this.form.recargo) {
                    let boni = 0;
                    let reca = 0;

                    if (this.form.bonificacion) {
                        boni =
                            (this.form.bonificacion * this.subtotalFactura) /
                            100;
                    }

                    if (this.form.recargo) {
                        reca = (this.form.recargo * this.subtotalFactura) / 100;
                    }

                    return this.subtotalFactura - boni + reca;
                } else {
                    return this.subtotalFactura;
                }
            } else {
                return null;
            }
        }
    },

    methods: {
        ...mapMutations(["FillTotal"]),
        ...mapActions("crudx", ["save"]),

        saveFactura() {
            if (this.$refs.formFactura.validate()) {
                if (this.form.detalle) {
                    this.form.subtotal = this.subtotalFactura;
                    this.form.total = this.total;
                    console.log(this.form);
                }
                //this.save({ url: "/api/facturas" });
            }
        },

        cancelFactura() {
            this.$refs.formFactura.reset();
        }
    }
};
</script>

<style>
.data {
    font-size: 12px;
    line-height: 5px;
    margin-top: 8px;
}

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
