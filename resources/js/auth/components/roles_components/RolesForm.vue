<template>
    <div>
        <v-layout justify-center wrap>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.role"
                    :rules="[rules.required]"
                    label="Rol"
                    color="primary"
                    box
                ></v-text-field>
                <Error tag="role"></Error>
            </v-flex>
            <v-flex xs12 px-3>
                <v-select
                    v-model="form.scope"
                    :items="showData"
                    item-text="description"
                    item-value="id"
                    label="Permisos"
                    color="primary"
                    multiple
                    chips
                    box
                ></v-select>
                <Error tag="permission"></Error>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Components
import Error from "../Error.vue";

// Vuex
import { mapState, mapActions } from "vuex";

export default {
    name: "RolesForm",

    data() {
        return {
            selected: [],
            rules: {
                required: value => !!value || "Este campo es obligatorio"
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
        this.show({ url: "/api/roles/show" });
    },

    methods: {
        ...mapActions("crudx", ["show"])
    }
};
</script>
