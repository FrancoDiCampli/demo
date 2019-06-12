<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm5 mx-1>
                <v-text-field v-model="cliente" label="Cliente" hint="CLiente" box single-line></v-text-field>
            </v-flex>
            <v-flex xs12 sm5 mx-1></v-flex>
        </v-layout>
    </div>
</template>

<script>
//Axios
import axios from "axios";

export default {
    name: "FacturasClientes",

    data() {
        return {
            cliente: null
        };
    },

    methods: {
        find() {
            axios
                .get("api/clientes", {
                    params: {
                        buscarCliente: this.cliente,
                        limit: 5
                    }
                })
                .then(response => {
                    commit("fillData", response.data);
                    state.inProcess = false;
                    resolve(response.data);
                })
                .catch(error => {
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        }
    }
};
</script>
