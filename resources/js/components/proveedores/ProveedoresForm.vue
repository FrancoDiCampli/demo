<template>
    <div>
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="form.cuit"
                    :rules="[rules.required]"
                    type="number"
                    label="Documento"
                    box
                ></v-text-field>
                <Error tag="cuit"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="form.razonsocial"
                    :rules="[rules.required, rules.max]"
                    label="Apellido y Nombre"
                    class="capitalize"
                    box
                ></v-text-field>
                <Error tag="razonsocial"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="form.telefono"
                    type="number"
                    class="input-number"
                    label="TEL/CEL"
                    box
                ></v-text-field>
                <Error tag="telefono"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field v-model="form.email" label="Email" box></v-text-field>
                <Error tag="email"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.direccion"
                    :rules="[rules.required, rules.max]"
                    label="Domicilio"
                    class="capitalize"
                    box
                ></v-text-field>
                <Error tag="direccion"></Error>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Components
import Error from "../../crudx/error.vue";

// Vuex
import { mapState } from "vuex";

export default {
    name: "ProveedoresForm",

    components: {
        Error
    },

    data() {
        return {
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 190) ||
                    "Este campo no puede contener mas de 190 digitos"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"])
    }
};
</script>

<style>
</style>