<template>
    <div>
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
                        <v-text-field
                            v-model="form.tipo"
                            label="Comprobante"
                            hint="Comprobante"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs12 sm5 mx-1>
                <v-layout wrap>
                    <v-flex xs12>
                        <v-text-field
                            v-model="subtotal"
                            disabled
                            label="Subtotal"
                            hint="Subtotal"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            v-model="total"
                            disabled
                            label="Total"
                            hint="Total"
                            box
                            single-line
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Vuex
import { mapState } from "vuex";

export default {
    name: "FacturasResumen",

    computed: {
        ...mapState("crudx", ["form"]),

        subtotal() {
            if (this.form.detalle) {
                let sub = 0;
                for (let i = 0; i < this.form.detalle.length; i++) {
                    sub += this.form.detalle[i].price;
                }
                return sub;
            }
        },

        total() {
            return null;
        }
    }
};
</script>

<style>
</style>
