<template>
    <div>
        <v-layout row>
            <v-flex xs12>
                <v-card>
                    <div v-if="mode == 'edit'">
                        <v-card-title>
                            <v-layout justify-space-between>
                                <h2>Editar Proveedor</h2>
                            </v-layout>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form ref="proveedorEditForm" @submit.prevent="updateProveedor">
                                <ProveedoresForm mode="edit"></ProveedoresForm>
                                <v-layout justify-center>
                                    <v-btn
                                        :disabled="inProcess"
                                        @click="mode = 'show'"
                                        outline
                                        color="primary"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :loading="inProcess"
                                        :disabled="inProcess"
                                        type="submit"
                                        color="primary"
                                    >Editar</v-btn>
                                </v-layout>
                            </v-form>
                        </v-card-text>
                    </div>
                    <div v-else>
                        <br />
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn absolute right flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile @click="editProveedor()">
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile @click="mode = 'delete'">
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                        <v-flex xs12>
                            <v-layout justify-center>
                                <v-avatar class="profile" size="86">
                                    <span class="display-1">{{ supllierProfile }}</span>
                                </v-avatar>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12>
                            <br />
                            <h1
                                class="text-xs-center primary--text"
                            >{{ showData.proveedor.razonsocial }}</h1>
                        </v-flex>
                        <v-flex xs12>
                            <h3 class="text-xs-center primary--text">{{ showData.proveedor.cuit }}</h3>
                        </v-flex>
                        <br />
                        <div v-if="mode == 'show'">
                            <v-tabs
                                fixed-tabs
                                grow
                                slider-color="primary"
                                active-class="primary--text"
                            >
                                <v-tab>Datos</v-tab>
                                <v-tab>Compras</v-tab>
                                <v-tab-item>
                                    <ProveedoresShowData></ProveedoresShowData>
                                </v-tab-item>
                                <v-tab-item>
                                    <div v-if="showData.remitos.length > 0">
                                        <ProveedoresShowCompras></ProveedoresShowCompras>
                                    </div>
                                    <div v-else>
                                        <br />
                                        <h2
                                            class="text-xs-center"
                                        >No se ha realizado ninguna compra a este Proveedor</h2>
                                    </div>
                                </v-tab-item>
                            </v-tabs>
                        </div>
                        <div v-else-if="mode == 'delete'">
                            <v-alert :value="true" color="error">
                                <h2 class="text-xs-center">¿Estas Seguro?</h2>
                                <br />
                                <v-divider dark></v-divider>
                                <br />
                                <p class="text-xs-center">¿Realmente deseas eliminar este Proveedor?</p>
                                <p class="text-xs-center">Este Cambio es Irreversible</p>
                                <br />
                                <v-layout justify-center>
                                    <v-btn
                                        :disabled="inProcess"
                                        @click="mode = 'show'"
                                        class="elevation-0 red--text"
                                        color="white"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :loading="inProcess"
                                        :disabled="inProcess"
                                        @click="deleteProveedor()"
                                        outline
                                        color="white"
                                    >Eliminar</v-btn>
                                </v-layout>
                            </v-alert>
                        </div>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Vuex
import { mapState, mapMutations, mapActions } from "vuex";

// Components
import ProveedoresShowData from "./ProveedoresShowData.vue";
import ProveedoresShowCompras from "./ProveedoresShowCompras.vue";
import ProveedoresForm from "./ProveedoresForm.vue";

export default {
    name: "ProveedoresShow",

    components: {
        ProveedoresShowData,
        ProveedoresShowCompras,
        ProveedoresForm
    },

    data() {
        return {
            mode: "show"
        };
    },

    computed: {
        ...mapState("crudx", ["showData", "form", "inProcess"]),

        supllierProfile() {
            if (this.showData.proveedor.razonsocial && this.mode != "edit") {
                let arrayname = this.showData.proveedor.razonsocial.split(" ");
                let profile = "";

                for (let i = 0; i < arrayname.length; i++) {
                    for (let e = 0; e < 1; e++) {
                        profile = profile + arrayname[i][e];
                    }
                }

                return profile;
            }
        }
    },

    methods: {
        ...mapActions("crudx", ["index", "show", "edit", "update", "destroy"]),

        editProveedor: async function() {
            await this.edit({ data: this.showData.proveedor });
            this.mode = "edit";
        },

        updateProveedor: async function() {
            if (this.$refs.proveedorEditForm.validate()) {
                let id = this.form.id;
                await this.update({ url: "/api/suppliers/" + id });
                await this.show({ url: "/api/suppliers/" + id });
                this.mode = "show";
                this.$refs.proveedorEditForm.reset();
                this.index({ url: "/api/suppliers" });
            }
        },

        deleteProveedor: async function() {
            await this.destroy({
                url: "/api/suppliers/" + this.showData.proveedor.id
            });
            await this.index({ url: "/api/suppliers" });
            this.mode = "show";
            this.$router.push("/proveedores");
        }
    }
};
</script>

<style>
</style>