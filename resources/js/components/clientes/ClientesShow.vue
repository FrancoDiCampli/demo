<template>
    <div>
        <v-layout justify-center wrap v-show="mode != 'edit'">
            <v-menu>
                <template v-slot:activator="{ on }">
                    <v-btn absolute right flat icon dark color="primary" v-on="on">
                        <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-tile @click="editCliente()">
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
                        <span class="display-1">{{ clientProfile }}</span>
                    </v-avatar>
                </v-layout>
            </v-flex>
            <v-flex xs12>
                <br />
                <h1 class="text-xs-center primary--text">{{ showData.cliente.razonsocial }}</h1>
            </v-flex>
            <v-flex xs12>
                <h3 class="text-xs-center primary--text">{{ showData.cliente.documentounico }}</h3>
            </v-flex>
        </v-layout>
        <br />
        <template>
            <div v-if="mode == 'show'">
                <v-tabs fixed-tabs grow slider-color="primary" active-class="primary--text">
                    <v-tab>Datos</v-tab>
                    <v-tab>Compras</v-tab>
                    <v-tab>Resumen Corriente</v-tab>
                    <v-tab-item>
                        <DataIterator></DataIterator>
                    </v-tab-item>
                    <v-tab-item>
                        <div v-if="showData.facturas.length > 0">
                            <FacturasTable></FacturasTable>
                        </div>
                        <div v-else>
                            <br />
                            <h2
                                class="text-xs-center"
                            >El cliente no ha realizado ninguna compra aun.</h2>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div v-if="showData.cuentas.length > 0">
                            <CuentaTable></CuentaTable>
                        </div>
                        <div v-else>
                            <br />
                            <h2
                                class="text-xs-center"
                            >El cliente no tiene cuentas corrientes en su haber.</h2>
                        </div>
                    </v-tab-item>
                </v-tabs>
            </div>
            <div v-else-if="mode == 'edit'">
                <v-form ref="clientesEditForm" @submit.prevent="updateCliente">
                    <ClientesForm mode="edit"></ClientesForm>
                    <v-layout justify-center>
                        <v-btn @click="mode = 'show'" outline color="primary">Cancelar</v-btn>
                        <v-btn :disabled="inProcess" type="submit" color="primary">Editar</v-btn>
                    </v-layout>
                </v-form>
            </div>
            <div v-else-if="mode == 'delete'">
                <v-alert :value="true" color="error">
                    <h2 class="text-xs-center">¿Estas Seguro?</h2>
                    <br />
                    <v-divider dark></v-divider>
                    <br />
                    <p class="text-xs-center">¿Realmente deseas eliminar este Cliente?</p>
                    <p class="text-xs-center">Este Cambio es Irreversible</p>
                    <br />
                    <v-layout justify-center>
                        <v-btn
                            @click="mode = 'show'"
                            class="elevation-0 red--text"
                            color="white"
                        >Cancelar</v-btn>
                        <v-btn @click="deleteCliente()" outline color="white">Eliminar</v-btn>
                    </v-layout>
                </v-alert>
            </div>
        </template>
    </div>
</template>

<script>
// Vuex
import { mapState, mapMutations, mapActions } from "vuex";

// Components
import DataIterator from "./ClientesShowDataIterator.vue";
import FacturasTable from "./ClientesShowFacturasTable.vue";
import CuentaTable from "./ClientesShowCuentaTable.vue";
import ClientesForm from "./ClientesForm.vue";

export default {
    name: "ClientesShow",

    data() {
        return {
            mode: "show"
        };
    },

    components: {
        DataIterator,
        FacturasTable,
        CuentaTable,
        ClientesForm
    },

    computed: {
        ...mapState("crudx", ["showData", "form", "inProcess"]),

        clientProfile() {
            if (this.showData.cliente.razonsocial && this.mode != "edit") {
                let arrayname = this.showData.cliente.razonsocial.split(" ");
                let profile = "";

                for (let i = 0; i < 2; i++) {
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

        editCliente: async function() {
            await this.edit({ data: this.showData.cliente });
            this.mode = "edit";
        },

        updateCliente: async function() {
            if (this.$refs.clientesEditForm.validate()) {
                let id = this.form.id;
                await this.update({ url: "/api/clientes/" + id });
                this.$refs.clientesEditForm.reset();
                await this.show({ url: "/api/clientes/" + id });
                this.mode = "show";
                this.index({ url: "/api/clientes/index" });
            }
        },

        deleteCliente: async function() {
            await this.destroy({
                url: "/api/clientes/" + this.showData.cliente.id
            });
            await this.index({ url: "/api/clientes" });
            this.mode = "show";
            this.$router.push("/clientes");
        }
    }
};
</script>

<style>
.profile {
    border: solid 3px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.profile span {
    color: #26a69a;
}
</style>
