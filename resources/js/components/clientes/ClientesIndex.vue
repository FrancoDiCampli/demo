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
                        <v-btn @click="showCliente(cliente.item.id)" flat icon color="primary">
                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </template>

        <!-- Clientes Show -->
        <v-dialog v-model="showClientesDialog" width="750" persistent>
            <v-card>
                <v-card-text style="overflow: hidden;">
                    <ClientesShow></ClientesShow>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import ClientesShow from "./ClientesShow.vue";
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

    components: {
        ClientesShow
    },

    computed: {
        ...mapState(["showClientesDialog"]),
        ...mapState("crudx", ["data"])
    },

    mounted() {
        this.index({ url: "api/clientes" });
    },

    methods: {
        ...mapMutations(["ClientesDialog"]),
        ...mapActions("crudx", ["index", "show"]),

        showCliente: async function(id) {
            await this.show({ url: "api/clientes/" + id });
            this.ClientesDialog();
        }
    }
};
</script>

<style>
</style>
