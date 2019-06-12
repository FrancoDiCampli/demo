<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="cuit"
                    @keyup="find()"
                    @click="razon == null"
                    label="Cuit"
                    hint="Cuit"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="razon"
                    @keyup="find()"
                    @click="cuit == null"
                    label="Razón Social"
                    hint="Razón Social"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
        <div v-if="clientes != null">
            <v-data-table
                :headers="clientesFindHeaders"
                :items="clientes"
                hide-actions
                hide-headers
            >
                <template v-slot:items="cliente">
                    <tr @click="selected = cliente.item.id">
                        <td>
                            <v-radio-group v-model="selected" style="margin-top: 20px;">
                                <v-radio color="primary" :value="cliente.item.id"/>
                            </v-radio-group>
                        </td>
                        <td class="hidden-xs-only">{{ cliente.item.documentounico }}</td>
                        <td>{{ cliente.item.razonsocial }}</td>
                        <td class="hidden-sm-and-down">{{ cliente.item.condicioniva }}</td>
                    </tr>
                </template>
            </v-data-table>
            <v-layout justify-center>
                <v-btn :disabled="selected == null" color="primary">Aceptar</v-btn>
            </v-layout>
        </div>
        <div v-else>
            <div v-if="cuit != null || razon != null">formulario</div>
        </div>
    </div>
</template>

<script>
//Axios
import axios from "axios";

//Vuex
import { mapMutations } from "vuex";

export default {
    name: "FindCliente",

    data() {
        return {
            cuit: null,
            razon: null,
            selected: null,
            clientesFindHeaders: [
                { text: "", sortable: false },
                { text: "CUIL/CUIT", sortable: false, class: "hidden-xs-only" },
                { text: "Apellido y Nombre", sortable: false },
                {
                    text: "Condición de IVA",
                    sortable: false,
                    class: "hidden-sm-and-down"
                }
            ],
            clientes: null,
            msg: ""
        };
    },

    methods: {
        ...mapMutations("crudx", ["fillForm"]),

        find() {
            this.selected = null;
            if (
                (this.cuit != null && this.cuit != "") ||
                (this.razon != null && this.razon != "")
            ) {
                axios
                    .get("/api/clientes", {
                        params: {
                            cuit: this.cuit,
                            razonsocial: this.razon
                        }
                    })
                    .then(response => {
                        if (response.data.length > 0) {
                            this.clientes = response.data;
                        } else {
                            if (this.cuit.length == 11) {
                                this.buscarAfip();
                                console.log("buscar afip");
                            } else {
                                this.msg =
                                    "¿Desea registrarlo con los datos ingresados manualmente?";
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.clientes = null;
            }
        },

        buscarAfip() {
            this.clientes == null;
            axios
                .get("api/buscarAfip/" + this.cuit)
                .then(response => {
                    if (response.data != null) {
                        this.fillData(response.data);
                        this.msg =
                            "¿Desea registrarlo con los datos obtenidos de AFIP?";
                    } else {
                        this.msg =
                            "¿Desea registrarlo con los datos ingresados manualmente?";
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },

        fillData(data) {
            let formData = {};

            // Documento y Condición de Iva
            if (data.tipoClave == "CUIL") {
                formData.condicioniva = "CONSUMIDOR FINAL";
            } else if (data.tipoClave == "CUIT") {
                for (let i = 0; i < data.impuesto.length; i++) {
                    if (data.impuesto[i].estado == "ACTIVO") {
                        if (data.impuesto[i].idImpuesto == 20) {
                            formData.condicioniva = "RESPONSABLE MONOTRIBUTO";
                        } else if (data.impuesto[i].idImpuesto == 30) {
                            formData.condicioniva = "IVA RESPONSABLE INSCRIPTO";
                        } else if (data.impuesto[i].idImpuesto == 32) {
                            formData.condicioniva = "IVA SUJENTO EXENTO";
                        }
                    }
                }
            }

            // Razon Social
            if (data.razonSocial) {
                formData.razonsocial = data.razonSocial;
            } else {
                formData.razonsocial = data.apellido;
            }

            // Documento
            formData.documentounico = data.idPersona;

            // Direccion
            if (data.domicilio.length) {
                for (let i = 0; i < data.domicilio.length; i++) {
                    if (data.domicilio[i].tipoDomicilio == "FISCAL") {
                        formData.codigopostal = data.domicilio[i].codPostal;
                        formData.direccion = data.domicilio[i].direccion;
                        formData.provincia =
                            data.domicilio[i].descripcionProvincia;
                        formData.localidad = data.domicilio[i].localidad;
                    }
                }
            } else {
                formData.codigopostal = data.domicilio.codPostal;
                formData.direccion = data.domicilio.direccion;
                formData.provincia = data.domicilio.descripcionProvincia;
                formData.localidad = data.domicilio.localidad;
            }

            this.fillForm(formData);
        }
    }
};
</script>

<style>
</style>
