<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="cuit"
                    @keyup="find()"
                    label="Cuit"
                    hint="Cuit"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="razon"
                    @keyup="find()"
                    label="Razón Social"
                    hint="Razón Social"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
        <div v-if="clientes != null">
            <v-data-table
                :headers="clientesFindHeaders"
                :items="clientes"
                hide-actions
                hide-headers
            >
                <template v-slot:items="cliente">
                    <tr
                        @click="selected = cliente.item.id"
                        style="cursor: pointer;"
                        :style="selected == cliente.item.id ?
                        'background-color: #26A69A; color: white;' : ''"
                    >
                        <td class="hidden-xs-only">{{ cliente.item.documentounico }}</td>
                        <td>{{ cliente.item.razonsocial }}</td>
                    </tr>
                </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "FindCliente",

    data() {
        return {
            cuit: null,
            razon: null,
            selected: null,
            clientesFindHeaders: [
                { text: "CUIL/CUIT", sortable: false, class: "hidden-xs-only" },
                { text: "Apellido y Nombre", sortable: false }
            ],
            clientes: null
        };
    },

    methods: {
        find() {
            this.selected = null;
            if (
                (this.cuit != null && this.cuit != "") ||
                (this.razon != null && this.razon != "")
            ) {
                axios
                    .get("/api/clientes", {
                        params: {
                            cuit: this.cuit,
                            razonsocial: this.razon
                        }
                    })
                    .then(response => {
                        this.clientes = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
            } else {
                this.clientes = null;
            }
        }
    }
};
</script>

<style>
</style>
