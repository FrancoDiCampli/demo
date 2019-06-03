<template>
    <div>
        <!-- Clientes Table -->
        <template>
            <v-data-table hide-actions :headers="headers" :items="data">
                <template v-slot:items="cliente">
                    <td class="hidden-xs-only">{{ cliente.item.documentounico }}</td>
                    <td>{{ cliente.item.razonsocial }}</td>
                    <td class="hidden-sm-and-down">{{ cliente.item.condicioniva }}</td>
                    <td>
                        <v-btn flat icon color="primary">
                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </template>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
    name: "ClientesIndex",

    data() {
        return {
            headers: [
                { text: "CUIL/CUIT", sortable: false, class: "hidden-xs-only" },
                { text: "Apellido y Nombre", sortable: false },
                {
                    text: "Condición de IVA",
                    sortable: false,
                    class: "hidden-sm-and-down"
                },
                { text: "", sortable: false }
            ]
        };
    },

    computed: {
        ...mapState("crudx", ["data"])
    },

    mounted() {
        this.index({ url: "api/clientes" });
    },

    methods: {
        ...mapActions("crudx", ["index"])
    }
};
</script>

<style>
</style>
