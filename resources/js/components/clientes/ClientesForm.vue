<template>
    <div>
        <v-layout wrap justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="form.documentounico"
                    :rules="[rules.required]"
                    @keyup="findCliente()"
                    type="number"
                    class="input-number"
                    label="Documento"
                    hint="Documento"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-select
                    v-model="form.condicioniva"
                    :rules="[rules.required, rules.max]"
                    :items="condiciones"
                    label="Condición Frente al IVA"
                    hint="Condición Frente al IVA"
                    single-line
                    box
                ></v-select>
            </v-flex>
        </v-layout>
        <v-layout justify-center v-show="inProcess">
            <v-flex xs12 sm11>
                <v-layout>
                    <v-progress-linear :indeterminate="true"></v-progress-linear>
                </v-layout>
            </v-flex>
        </v-layout>
        <v-layout wrap justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="form.razonsocial"
                    :rules="[rules.required, rules.max]"
                    label="Apellido y Nombre"
                    hint="Apellido y Nombre"
                    class="capitalize"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="form.telefono"
                    type="number"
                    class="input-number"
                    label="TEL/CEL"
                    hint="TEL/CEL"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-layout justify-center>
            <v-flex xs12 sm11>
                <v-text-field v-model="form.email" label="Email" hint="Email" box single-line></v-text-field>
            </v-flex>
        </v-layout>
        <v-layout justify-center>
            <v-flex xs12 sm11>
                <v-text-field
                    v-model="form.direccion"
                    :rules="[rules.required, rules.max]"
                    label="Domicilio"
                    hint="Domicilio"
                    class="capitalize"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-layout wrap justify-space-around>
            <v-flex xs12 sm3>
                <v-text-field
                    v-model="form.provincia"
                    :rules="[rules.required, rules.max]"
                    label="Provincia"
                    hint="Provincia"
                    class="capitalize"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm3>
                <v-text-field
                    v-model="form.localidad"
                    :rules="[rules.required, rules.max]"
                    label="Localidad"
                    hint="Localidad"
                    class="capitalize"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm3>
                <v-text-field
                    v-model="form.codigopostal"
                    :rules="[rules.required, rules.minPostal, rules.maxPostal]"
                    type="number"
                    class="input-number"
                    label="Codigo Postal"
                    hint="Codigo Postal"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import Vuex, { mapState, mapActions } from "vuex";
import axios from "axios";
export default {
    name: "ClientesCreate",

    data() {
        return {
            disabled: false,
            condiciones: [
                "CONSUMIDOR FINAL",
                "IVA RESPONSABLE INSCRIPTO",
                "IVA SUJENTO EXENTO",
                "RESPONSABLE MONOTRIBUTO"
            ],

            msg: "",

            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 190) ||
                    "Este campo no puede contener mas de 11 digitos",
                minPostal: value =>
                    (value && value.length >= 4) ||
                    "El campo Codigo Postal debe contener como minimo 6 digitos",
                maxPostal: value =>
                    (value && value.length <= 4) ||
                    "El campo Codigo Postal no puede contener mas de 13 digitos"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    methods: {
        findCliente() {
            if (this.form.documentounico.length >= 8) {
                axios
                    .get("api/clientes", {
                        params: {
                            documentounico: this.form.documentounico
                        }
                    })
                    .then(response => {
                        if (response.data) {
                            this.msg = "El cliente ya existe";
                            console.log("El cliente ya existe");
                            console.log(response.data);
                        } else {
                            console.log("buscando afip");
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
};
</script>

<style>
.input-number input[type="number"] {
    -moz-appearance: textfield;
}
.input-number input::-webkit-outer-spin-button,
.input-number input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

.capitalize input[type] {
    text-transform: capitalize;
}
</style>
