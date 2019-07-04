<template>
    <div>
        <template>
            <div class="loading" v-show="process">
                <v-layout justify-center>
                    <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                </v-layout>
            </div>
        </template>
        <v-layout justify-center>
            <v-flex xs12 px-3>
                <v-alert
                    :value="alertCliente"
                    color="error"
                    class="text-xs-center"
                >Ya existe un cliete registrado con este documento</v-alert>
                <br />
            </v-flex>
        </v-layout>
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 px-3>
                <Error tag="documentounico"></Error>
                <v-text-field
                    :disabled="process"
                    v-model="form.documentounico"
                    :rules="[rules.required]"
                    @keyup="findCliente()"
                    type="number"
                    label="Documento"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <Error tag="condicioniva"></Error>
                <v-select
                    :disabled="disabled"
                    v-model="form.condicioniva"
                    :rules="[rules.required, rules.max]"
                    :items="condiciones"
                    label="Condición Frente al IVA"
                    box
                ></v-select>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <Error tag="razonsocial"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.razonsocial"
                    :rules="[rules.required, rules.max]"
                    label="Apellido y Nombre"
                    class="capitalize"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <Error tag="telefono"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.telefono"
                    type="number"
                    class="input-number"
                    label="TEL/CEL"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 px-3>
                <Error tag="email"></Error>
                <v-text-field :disabled="disabled" v-model="form.email" label="Email" box></v-text-field>
            </v-flex>
            <v-flex xs12 px-3>
                <Error tag="direccion"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.direccion"
                    :rules="[rules.required, rules.max]"
                    label="Domicilio"
                    class="capitalize"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 px-3>
                <Error tag="provincia"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.provincia"
                    :rules="[rules.required, rules.max]"
                    label="Provincia"
                    class="capitalize"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 px-3>
                <Error tag="localidad"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.localidad"
                    :rules="[rules.required, rules.max]"
                    label="Localidad"
                    class="capitalize"
                    box
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 px-3>
                <Error tag="codigopostal"></Error>
                <v-text-field
                    :disabled="disabled"
                    v-model="form.codigopostal"
                    :rules="[rules.required]"
                    type="number"
                    class="input-number"
                    label="Codigo Postal"
                    box
                ></v-text-field>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import Error from "../../crudx/error.vue";
import Vuex, { mapState, mapActions, mapMutations } from "vuex";
import axios from "axios";
export default {
    name: "ClientesCreate",

    props: ["mode"],

    data() {
        return {
            alertCliente: false,
            disabled: false,
            documentoExistente: null,
            condiciones: [
                "CONSUMIDOR FINAL",
                "IVA RESPONSABLE INSCRIPTO",
                "IVA SUJENTO EXENTO",
                "RESPONSABLE MONOTRIBUTO"
            ],
            process: false,
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 190) ||
                    "Este campo no puede contener mas de 190 digitos"
            }
        };
    },

    components: {
        Error
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    mounted() {
        if (this.mode == "edit") {
            this.documentoExistente = this.form.documentounico;
        } else if (this.mode == "new") {
            this.documentoExistente = null;
        }
    },

    methods: {
        ...mapMutations("crudx", ["fillForm"]),

        findCliente() {
            if (this.form.documentounico) {
                this.alertCliente = false;
                this.disabled = false;
                if (this.form.documentounico.length == 11) {
                    this.process = true;
                    this.disabled = true;

                    axios
                        .get("/api/clientes", {
                            params: {
                                buscarCliente: this.form.documentounico,
                                limit: 1
                            }
                        })
                        .then(response => {
                            if (response.data.total > 0) {
                                this.process = false;
                                if (this.mode == "new") {
                                    this.alertCliente = true;
                                } else if (this.mode == "edit") {
                                    if (
                                        this.documentoExistente ==
                                        this.form.documentounico
                                    ) {
                                        this.alertCliente = false;
                                        this.disabled = false;
                                    } else {
                                        this.alertCliente = true;
                                        this.disabled = true;
                                    }
                                }
                            } else {
                                this.buscarAfip();
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            this.process = false;
                            this.disabled = false;
                        });
                }
            }
        },

        buscarAfip() {
            axios
                .get("/api/buscarAfip/" + this.form.documentounico)
                .then(response => {
                    if (response.data != null) {
                        this.fillData(response.data);
                    } else {
                        this.process = false;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.log(error);
                    this.process = false;
                    this.disabled = false;
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
            this.process = false;
            this.disabled = false;
        }
    }
};
</script>

<style>
input[type="number"] {
    -moz-appearance: textfield;
}
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

.capitalize input[type] {
    text-transform: capitalize;
}

.loading {
    position: fixed;
    z-index: 999999;
    left: 47.3%;
    top: 44%;
}
</style>
