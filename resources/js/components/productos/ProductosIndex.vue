<template>
    <div>
        <!-- Productos Table -->
        <v-btn @click="log()">log</v-btn>
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
                                <v-layout justify-space-between column fill-height>
                                    <div>
                                        <v-layout justify-end>
                                            <div
                                                @click="showProductos(articulo)"
                                                class="tringle-right-button"
                                            >
                                                <i class="fas fa-ellipsis-v icon"></i>
                                            </div>
                                        </v-layout>
                                    </div>
                                    <div>
                                        <v-layout justify-start>
                                            <div v-if="articulo.stock.length > 0">
                                                <div
                                                    v-if="
                                                    articulo.stock[0].total <= articulo.stockminimo &&
                                                    articulo.stock[0].total > 0
                                                    "
                                                >
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #FF8F00;"
                                                    >
                                                        <i class="fas fa-box-open icon"></i>
                                                    </div>
                                                </div>
                                                <div v-else-if="articulo.stock[0].total == 0">
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #FF5252;"
                                                    >
                                                        <i class="fas fa-exclamation-circle icon"></i>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div
                                                        class="tringle-left-button"
                                                        style="color: #4CAF50;"
                                                    >
                                                        <i class="fas fa-check icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <div
                                                    class="tringle-left-button"
                                                    style="color: #FF5252;"
                                                >
                                                    <i class="fas fa-exclamation-circle icon"></i>
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
                                        <div v-if="articulo.item.stock.length <= 0">0</div>
                                        <div v-else>{{ articulo.item.stock[0].total }}</div>
                                    </td>
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
                { text: "Stock", sortable: false },
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
            await this.index({
                url: "/api/articulos",
                limit: this.limit
            });
            this.loadingButton = false;
        },

        showProductos: async function(articulo) {
            await this.show({ url: "/api/articulos/" + articulo.id });
            this.showProductosDialog = true;
        },

        log() {
            console.log(this.data);
        }
    }
};
</script>

<style>
.tringle-right-button {
    position: relative;
    width: 70px;
    height: 70px;
    border-top: solid 35px #26a69a;
    border-right: solid 35px #26a69a;
    border-left: solid 35px transparent;
    border-bottom: solid 35px transparent;
    cursor: pointer;
}

.tringle-right-button .icon {
    position: absolute;
    margin-top: -22px;
    margin-left: 10px;
    color: white;
    font-size: 16px;
}

.tringle-left-button {
    position: relative;
    width: 50px;
    height: 50px;
    border-top: solid 25px transparent;
    border-right: solid 25px transparent;
    border-left: solid 25px;
    border-bottom: solid 25px;
}

.tringle-left-button .icon {
    position: absolute;
    margin-top: 2px;
    margin-left: -18px;
    color: white;
    font-size: 16px;
}

@media (min-width: 600px) {
    .tringle-right-button {
        width: 60px;
        height: 60px;
        border-top: solid 30px #26a69a;
        border-right: solid 30px #26a69a;
        border-left: solid 30px transparent;
        border-bottom: solid 30px transparent;
    }

    .tringle-right-button .icon {
        margin-top: -20px;
        margin-left: 8px;
    }

    .tringle-left-button {
        width: 60px;
        height: 60px;
        border-top: solid 30px transparent;
        border-right: solid 30px transparent;
        border-left: solid 30px;
        border-bottom: solid 30px;
    }

    .tringle-left-button .icon {
        margin-top: 4px;
        margin-left: -18px;
    }
}

@media (min-width: 1264px) {
    .tringle-right-button {
        width: 50px;
        height: 50px;
        border-top: solid 25px #26a69a;
        border-right: solid 25px #26a69a;
        border-left: solid 25px transparent;
        border-bottom: solid 25px transparent;
    }

    .tringle-right-button .icon {
        margin-top: -16px;
        margin-left: 8px;
        font-size: 14px;
    }
}
</style>
