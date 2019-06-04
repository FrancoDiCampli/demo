<template>
    <div>
        <v-layout justify-center wrap>
            <v-flex xs12>
                <v-layout justify-center>
                    <v-avatar class="profile" size="86">
                        <span class="display-1">{{ clientProfile }}</span>
                    </v-avatar>
                </v-layout>
            </v-flex>
            <v-flex xs12>
                <br>
                <h1 class="text-xs-center primary--text">{{ showData.razonsocial }}</h1>
            </v-flex>
            <v-flex xs12>
                <h3 class="text-xs-center primary--text">{{ showData.documentounico }}</h3>
            </v-flex>
        </v-layout>
        <br>
        <template>
            <v-tabs fixed-tabs grow slider-color="primary">
                <v-tab>Datos</v-tab>
                <v-tab>Compras</v-tab>
                <v-tab>Cuenta Corriente</v-tab>
                <v-tab-item>
                    <br>
                    <v-layout wrap>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Documento Unico:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.documentounico }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Condición Frente al Iva:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.condicioniva }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Razón Social:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.razonsocial }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Dirección:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.direccion }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>TEL/CEL:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.telefono }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Email:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.email }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Provincia:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.provincia }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>localidad:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.localidad }}</p>
                        </v-flex>
                        <v-flex xs6>
                            <p class="text-xs-right">
                                <b>Codigo Postal:</b>
                            </p>
                        </v-flex>
                        <v-flex xs6>
                            <p style="margin-left: 10px;">{{ showData.codigopostal }}</p>
                        </v-flex>
                    </v-layout>
                </v-tab-item>
                <v-tab-item>compras del cliente</v-tab-item>
                <v-tab-item>cuenta corriente del cliente</v-tab-item>
            </v-tabs>
        </template>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "ClientesShow",

    computed: {
        ...mapState("crudx", ["showData"]),

        clientProfile() {
            if (this.showData.razonsocial) {
                let arrayname = this.showData.razonsocial.split(" ");
                let profile = "";

                for (let i = 0; i < 2; i++) {
                    for (let e = 0; e < 1; e++) {
                        profile = profile + arrayname[i][e];
                    }
                }

                return profile;
            }
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
