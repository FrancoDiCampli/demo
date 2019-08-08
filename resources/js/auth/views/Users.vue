<template>
    <div>
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn
                    dark
                    fab
                    fixed
                    right
                    bottom
                    @click="createUsersDialog = !createUsersDialog"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Usuario</span>
        </v-tooltip>

        <v-dialog v-model="createUsersDialog" width="500" persistent>
            <v-card>
                <v-card-text>
                    <h2>Nuevo Usuario</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="usersForm" @submit.prevent="saveUser">
                        <UsersForm mode="create"></UsersForm>
                        <br />
                        <v-layout justify-end>
                            <v-btn
                                :disabled="inProcess"
                                @click="createUsersDialog = false"
                                outline
                                color="primary"
                            >Cancelar</v-btn>
                            <v-btn
                                :loading="inProcess"
                                :disabled="inProcess"
                                type="submit"
                                color="primary"
                            >Guardar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-card>
            <v-card-text>
                <UsersIndex></UsersIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

import UsersIndex from "../components/users_components/UsersIndex.vue";
import UsersForm from "../components/users_components/UsersForm.vue";

export default {
    name: "Users",

    data() {
        return {
            createUsersDialog: false
        };
    },

    components: {
        UsersIndex,
        UsersForm
    },

    computed: {
        ...mapState("crudx", ["form", "inProcess"])
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        saveUser: async function() {
            if (this.$refs.usersForm.validate()) {
                await this.save({ url: "/api/users" });
                this.$refs.usersForm.reset();
                this.index({ url: "/api/users" });
                this.createUsersDialog = false;
            }
        }
    }
};
</script>
