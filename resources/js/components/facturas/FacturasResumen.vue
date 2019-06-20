<template>
    <div>
        <v-container>
            <v-layout wrap justify-space-around>
                <v-flex xs12 md5>
                    <v-text-field
                        type="number"
                        class="input-number"
                        label="% Bonificacion"
                        hint="% Bonificacion"
                        box
                        single-line
                        v-model="bonificacion"
                    ></v-text-field>
                    <v-text-field
                        type="number"
                        class="input-number"
                        label="% Recargo"
                        hint="Recargo"
                        box
                        single-line
                        v-model="recargo"
                    ></v-text-field>
                    <v-text-field
                        type="number"
                        class="input-number"
                        label="Comprobante"
                        hint="Comprobante"
                        box
                        single-line
                    ></v-text-field>
                </v-flex>

                <v-flex xs12 md5>
                    <v-text-field
                        type="number"
                        class="input-number"
                        label="Sub Total"
                        hint="Sub Total"
                        box
                        single-line
                        v-model="subtotal"
                        disabled
                    ></v-text-field>
                    <v-text-field
                        v-model="total"
                        type="number"
                        class="input-number"
                        label="Total"
                        hint="Total"
                        box
                        single-line
                        disabled
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            subtotal: 200,
            bonificacion: 0,
            recargo: 0
        };
    },
    methods: {
        limpiar() {}
    },
    computed: {
        total() {
            return (
                parseFloat(this.subtotal) -
                parseFloat((this.bonificacion * this.subtotal) / 100) +
                parseFloat((this.recargo * this.subtotal) / 100)
            );
        }
    }
};
</script>

<style>
</style>
