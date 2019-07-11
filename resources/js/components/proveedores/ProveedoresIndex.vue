<template>
    <div>
        <template>
            <v-data-table
                hide-actions
                :headers="headers"
                :items="data.proveedores"
                :loading="inProcess"
            >
                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                <template v-slot:items="supplier">
                    <td class="hidden-xs-only">{{ supplier.item.cuit }}</td>
                    <td>{{ supplier.item.razonsocial }}</td>
                    <td>{{ supplier.item.direccion }}</td>
                    <td>{{ supplier.item.telefono }}</td>
                    <td>
                        <v-btn
                            @click="$router.push('/suppliers/show/' + supplier.item.id);"
                            flat
                            icon
                            color="primary"
                        >
                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </template>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    name: "ProveedoresIndex",

    data() {
        return {
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "CUIT", sortable: false, class: "hidden-xs-only" },
                { text: "Razón Social", sortable: false },
                { text: "Dirección", sortable: false },
                { text: "Teléfono", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },

    components: {
        // ProveedoresShow
    },

    computed: {
        ...mapState("crudx", ["data", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/suppliers", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index", "show"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "api/clientes", limit: this.limit });
            this.loadingButton = false;
        }
    }
};
</script>

<style>
</style>
