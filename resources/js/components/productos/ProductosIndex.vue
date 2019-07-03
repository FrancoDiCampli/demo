<template>
    <div>
        <!-- Productos Table -->
        <v-tabs
            v-model="tabsProductos"
            right
            hide-slider
            color="transparent"
            active-class="primary--text"
        >
            <v-tab>
                <v-icon size="medium" :color="tabsProductos == 0 ? 'primary' : ''">fas fa-th-large</v-icon>
            </v-tab>
            <v-tab>
                <v-icon size="medium" :color="tabsProductos == 1 ? 'primary' : ''">fas fa-th-list</v-icon>
            </v-tab>
            <v-tab-item>
                <v-layout justify-space-around wrap>
                    <v-flex xs12 sm6 lg4 pa-2 v-for="articulo in data.articulos" :key="articulo.id">
                        <v-card>
                            <v-img :src="articulo.foto" aspect-ratio="1.25">
                                <v-layout justify-end>
                                    <v-btn
                                        @click="showProductos(articulo)"
                                        flat
                                        icon
                                        color="primary"
                                    >
                                        <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                    </v-btn>
                                </v-layout>
                            </v-img>
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline">{{ articulo.articulo }}</h3>
                                    <div>
                                        <p>{{ articulo.codarticulo }}</p>
                                        <p>$ {{ articulo.precio }}</p>
                                    </div>
                                </div>
                            </v-card-title>
                        </v-card>
                    </v-flex>
                </v-layout>
                <br />
                <v-layout justify-center>
                    <v-btn
                        :loading="loadingButton"
                        :disabled="limit >= data.total || loadingButton"
                        @click="loadMore()"
                        color="primary"
                        outline
                    >Cargar Más</v-btn>
                </v-layout>
            </v-tab-item>
            <v-tab-item>
                <v-card>
                    <v-card-text>
                        <template>
                            <v-data-table
                                hide-actions
                                :headers="headers"
                                :items="data.articulos"
                                :loading="inProcess"
                            >
                                <v-progress-linear v-slot:progress color="primary" indeterminate></v-progress-linear>
                                <template v-slot:items="articulo">
                                    <td class="hidden-xs-only">{{ articulo.item.codarticulo }}</td>
                                    <td>{{ articulo.item.articulo }}</td>
                                    <td>$ {{ articulo.item.precio }}</td>
                                    <td>
                                        <v-btn
                                            @click="showProductos(articulo)"
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
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>

        <!-- Clientes Show -->
        <v-dialog v-model="showProductosDialog" width="750" persistent>
            <v-card>
                <v-card-text>
                    <v-layout justify-center wrap>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn absolute right flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile>
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                </v-list-tile>
                                <v-divider></v-divider>
                                <v-list-tile>
                                    <v-list-tile-title>Cerrar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                        <v-flex xs12>
                            <v-layout justify-center>
                                <v-avatar class="profile" size="180">
                                    <v-img :src="showData.foto"></v-img>
                                </v-avatar>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12>
                            <br />
                            <h1 class="text-xs-center primary--text">{{ showData.articulo }}</h1>
                        </v-flex>
                        <v-flex xs12>
                            <h3 class="text-xs-center primary--text">{{ showData.codarticulo }}</h3>
                        </v-flex>
                    </v-layout>
                    <br />
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
//Vuex
import { mapState, mapActions } from "vuex";

export default {
    name: "ProductosIndex",

    data() {
        return {
            tabsProductos: null,
            limit: 10,
            loadingButton: false,
            headers: [
                { text: "Codigo", sortable: false, class: "hidden-xs-only" },
                { text: "Articulo", sortable: false },
                { text: "Precio", sortable: false },
                { text: "", sortable: false }
            ],
            showProductosDialog: false
        };
    },

    computed: {
        ...mapState("crudx", ["data", "showData", "inProcess"])
    },

    mounted() {
        this.index({ url: "api/articulos", limit: this.limit });
    },

    methods: {
        ...mapActions("crudx", ["index", "show"]),

        loadMore: async function() {
            this.limit += this.limit;
            this.loadingButton = true;
            await this.index({ url: "/api/articulos", limit: this.limit });
            this.loadingButton = false;
        },

        showProductos: async function(articulo) {
            await this.show({ url: "/api/articulos/" + articulo.id });
            this.showProductosDialog = true;
        }
    }
};
</script>

<style>
</style>
