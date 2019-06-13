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
            <v-layout justify-space-around>
                <v-flex xs12 sm5 mx-1>
                    <v-layout wrap>
                        <v-flex xs12>
                            <v-text-field
                                v-model="form.bonificacion"
                                label="Bonificacion"
                                hint="Bonificacion"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                v-model="form.recargo"
                                label="Recargo"
                                hint="Recargo"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
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
                    <v-layout wrap>
                        <v-flex xs12>
                            <v-text-field
                                v-model="form.subtotal"
                                disabled
                                :rules="[rules.required]"
                                label="Subtotal"
                                hint="Subtotal"
                                box
                                single-line
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                v-model="form.total"
                                disabled
                                :rules="[rules.required]"
                                label="Total"
                                hint="Total"
                                box
                                single-line
                            ></v-text-field>
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
import { mapState, mapActions } from "vuex";

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
        ...mapState("crudx", ["form"])
    },

    updated() {
        //Subtotal de la Factura
        // if (this.details.length > 0) {
        //     let sub = 0;
        //     for (let i = 0; i < this.details.length; i++) {
        //         sub += this.details[i].subtotal * 1;
        //     }
        //     this.form.subtotal = sub;
        //     console.log("subtotal");
        //     console.log(sub);
        //     console.log(this.form.subtotal);
        // } else {
        //     this.form.subtotal = null;
        // }
        // //Total de la Factura
        // if (this.details.length > 0) {
        //     let sub = 0;
        //     for (let i = 0; i < this.details.length; i++) {
        //         sub += this.details[i].subtotal * 1;
        //     }
        //     let boni = 0;
        //     let reca = 0;
        //     if (this.form.bonificacion) {
        //         boni = (this.form.bonificacion * sub) / 100;
        //     }
        //     if (this.form.recargo) {
        //         reca = (this.form.recargo * sub) / 100;
        //     }
        //     sub = sub - boni + reca;
        //     this.form.total = sub;
        //     console.log("total");
        //     console.log(sub);
        //     console.log(this.form.total);
        // } else {
        //     this.form.total = null;
        // }
    },

    methods: {
        ...mapActions("crudx", ["save"]),
        saveFactura() {
            this.save({ url: "/api/facturas" });
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
