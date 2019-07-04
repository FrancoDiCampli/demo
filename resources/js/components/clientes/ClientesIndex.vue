<template>
    <div>
        <!-- Tabla de Clientes -->
        <template>
            <v-data-table
                hide-actions
                :headers="headers"
                :items="data.clientes"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="cliente">
                    <td class="hidden-xs-only">{{ cliente.item.documentounico }}</td>
                    <td>{{ cliente.item.razonsocial }}</td>
                    <td class="hidden-sm-and-down">{{ cliente.item.condicioniva }}</td>
                    <td>
                        <v-btn
                            @click="$router.push('/clientes/show/' + cliente.item.id);"
                            flat
                            icon
                            color="primary"
                        >
                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
            <v-layout justify-center>
                <v-btn
                    :loading="loadingButton"
                    :disabled="limit >= data.total || loadingButton"
                    @click="loadMore()"
                    color="primary"
                    outline
                >Cargar Más</v-btn>
            </v-layout>
        </template>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import ClientesShow from "./ClientesShow.vue";
export default {
    name: "ClientesIndex",

    data() {
        return {
            limit: 10,
            loadingButton: false,
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
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/clientes/index", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index", "show"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "api/clientes/index", limit: this.limit });
            this.loadingButton = false;
        }
    }
};
</script>

<style>
</style>
