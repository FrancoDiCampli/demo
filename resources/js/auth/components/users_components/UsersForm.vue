<template>
    <div>
        <v-layout justify-center wrap>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.name"
                    :rules="[rules.required]"
                    label="Nombre"
                    color="primary"
                    box
                ></v-text-field>
                <Error tag="name"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.email"
                    :rules="[rules.required]"
                    label="Email"
                    color="primary"
                    box
                ></v-text-field>
                <Error tag="email"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.password"
                    :rules="[rules.required, rules.max, rules.min]"
                    :append-icon="password ? 'fas fa-eye' : 'fas fa-eye-slash'"
                    @click:append="password = !password"
                    :type="password ? 'text' : 'password'"
                    label="Contraseña"
                    color="primary"
                    box
                ></v-text-field>
                <Error tag="password"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.password_confirm"
                    :rules="[rules.required, rules.max, rules.min]"
                    :append-icon="confirm_password ? 'fas fa-eye' : 'fas fa-eye-slash'"
                    @click:append="confirm_password = !confirm_password"
                    :type="confirm_password ? 'text' : 'password'"
                    label="Confirmar Contraseña"
                    color="primary"
                    box
                ></v-text-field>
                <Error tag="password_confirm"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-select
                    v-model="form.role_id"
                    :items="showData"
                    item-text="role"
                    item-value="id"
                    label="Rol"
                    color="primary"
                    box
                ></v-select>
                <Error tag="role_id"></Error>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Components
import Error from "../Error.vue";

// Vuex
import { mapState, mapActions } from "vuex";

export default {
    name: "UsersForm",

    data() {
        return {
            selected: "",
            password: false,
            confirm_password: false,
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 255) ||
                    "Este campo no puede contener mas de 255 digitos",
                min: value =>
                    (value && value.length >= 6) ||
                    "Este campo debe contener al menos 6 digitos"
            }
        };
    },

    components: {
        Error
    },

    computed: {
        ...mapState("crudx", ["form", "showData"])
    },

    mounted() {
        this.show({ url: "/api/roles" });
    },

    methods: {
        ...mapActions("crudx", ["show"])
    }
};
</script>