<template>
    <div>
        <!-- Users Table -->
        <template>
            <v-data-table hide-actions :headers="headers" :items="data">
                <template v-slot:items="user">
                    <td>{{ user.item.name }}</td>
                    <td>{{ user.item.email }}</td>
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile
                                    @click="edit({ data: user.item }); editUsersDialog = true;"
                                >
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile
                                    @click="userID = user.item.id; deleteUsersDialog = true"
                                    :disabled="account.user.id == user.item.id ? true : false"
                                >
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </template>
            </v-data-table>
        </template>
        <!-- Edit Users Dialog -->
        <v-dialog v-model="editUsersDialog" width="500" persistent>
            <v-card>
                <v-card-text>
                    <h2>Editar Usuario</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="userForm" @submit.prevent="updateUser()">
                        <UsersForm></UsersForm>
                        <br>
                        <v-layout justify-end>
                            <v-btn @click="editUsersDialog = false" outline color="error">Cancilar</v-btn>
                            <v-btn type="submit" color="primary">Editar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Delete Roles Dialog -->
        <v-dialog v-model="deleteUsersDialog" width="400" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>¿Estás seguro que deseas eliminar este Usuario? este cambio es irreversible</v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn @click="deleteUsersDialog = false;" outline color="success">Cancelar</v-btn>
                        <v-btn @click="erase()" color="error">Eliminar</v-btn>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import UsersForm from "./UsersForm.vue";
import { mapState, mapActions, mapGetters } from "vuex";

export default {
    name: "UserIndex",

    data() {
        return {
            editUsersDialog: false,
            deleteUsersDialog: false,
            userID: null,
            headers: [
                { text: "Name", sortable: false },
                { text: "Email", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },

    components: {
        UsersForm
    },

    computed: {
        ...mapState("crudx", ["data", "form"]),
        ...mapGetters("auth", ["account"])
    },

    created() {
        this.getUser();
    },

    mounted() {
        this.index({ url: "api/users/index" });
    },

    methods: {
        ...mapActions("crudx", ["index", "edit", "update", "destroy"]),
        ...mapActions("auth", ["getUser"]),
        updateUser: async function() {
            if (this.$refs.userForm.validate()) {
                await this.update({ url: "api/users/edit/" + this.form.id });
                this.index({ url: "api/users/index" });
                this.editUsersDialog = false;
            }
        },
        erase: async function() {
            await this.destroy({ url: "api/users/delete/" + this.userID });
            this.index({ url: "api/users/index" });
            this.userID = null;
            this.deleteUsersDialog = false;
        }
    }
};
</script>