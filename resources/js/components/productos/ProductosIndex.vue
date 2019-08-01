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
                <v-layout justify-start wrap>
                    <v-flex xs12 sm6 lg4 pa-2 v-for="articulo in data.articulos" :key="articulo.id">
                        <v-card>
                            <v-img :src="articulo.foto" aspect-ratio="1.25">
                                <v-layout justify-space-between column fill-height>
                                    <div>
                                        <v-layout justify-end>
                                            <div
                                                @click="$router.push('/productos/show/' + articulo.id);"
                                                class="tringle-right-button"
                                            >
                                                <i class="fas fa-ellipsis-v icon"></i>
                                            </div>
                                        </v-layout>
                                    </div>
                                    <div>
                                        <v-layout justify-start>
                                            <div v-if="articulo.vencido">
                                                <div
                                                    class="tringle-left-button"
                                                    style="color: #FF8F00;"
                                                >
                                                    <i class="fas fa-clock icon"></i>
                                                </div>
                                            </div>
                                            <div v-else-if="!articulo.vencido">
                                                <div
                                                    v-if="
                                                    articulo.stock <= articulo.stockminimo &&
                                                    articulo.stock > 0
                                                    "
                                                >
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #FF8F00;"
                                                    >
                                                        <i class="fas fa-box-open icon"></i>
                                                    </div>
                                                </div>
                                                <div
                                                    v-else-if="articulo.stock == 0 || articulo.inventarios.length <= 0"
                                                >
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #FF5252;"
                                                    >
                                                        <i class="fas fa-exclamation-circle icon"></i>
                                                    </div>
                                                </div>
                                                <div
                                                    v-else-if="articulo.stock > articulo.stockminimo"
                                                >
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #4CAF50;"
                                                    >
                                                        <i class="fas fa-check icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </v-layout>
                                    </div>
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
                                        <div v-if="articulo.item.stock <= 0">0</div>
                                        <div v-else>{{ articulo.item.stock }}</div>
                                    </td>
                                    <td>
                                        <v-btn
                                            @click="$router.push('/productos/show/' + articulo.item.id);"
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
                { text: "Stock", sortable: false },
                { text: "", sortable: false }
            ]
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
            await this.index({
                url: "/api/articulos",
                limit: this.limit
            });
            this.loadingButton = false;
        }
    }
};
</script>

<style>
</style>
