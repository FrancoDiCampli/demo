<template>
    <div>
        <!-- Roles Table -->
        <template>
            <v-data-table hide-actions :headers="headers" :items="data">
                <template v-slot:items="rol">
                    <td>{{ rol.item.role }}</td>
                    <td>{{ rol.item.permission }}</td>
                    <td>
                        <v-menu>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon dark color="primary" v-on="on">
                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-tile
                                    @click="edit({ data: rol.item }); editRolesDialog = true;"
                                >
                                    <v-list-tile-title>Editar</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile
                                    @click="roleID = rol.item.id; deleteRolesDialog = true"
                                >
                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </template>
            </v-data-table>
        </template>
        <!-- Edit Roles Dialog -->
        <v-dialog v-model="editRolesDialog" width="400" persistent>
            <v-card>
                <v-card-text>
                    <h2>Editar ROl</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="roleForm" @submit.prevent="updateRole()">
                        <RolesForm></RolesForm>
                        <br>
                        <v-layout justify-end>
                            <v-btn @click="editRolesDialog = false" outline color="error">Cancelar</v-btn>
                            <v-btn type="submit" color="primary">Editar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Delete Roles Dialog -->
        <v-dialog v-model="deleteRolesDialog" width="400" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>¿Estás seguro que deseas eliminar este Rol? este cambio es irreversible</v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn @click="deleteRolesDialog = false;" outline color="success">Cancelar</v-btn>
                        <v-btn @click="erase()" color="error">Eliminar</v-btn>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import RolesForm from "./RolesForm.vue";
import { mapState, mapActions } from "vuex";

export default {
    name: "RoleIndex",

    data() {
        return {
            editRolesDialog: false,
            deleteRolesDialog: false,
            roleID: null,
            headers: [
                { text: "Rol", sortable: false },
                { text: "Permission", sortable: false },
                { text: "", sortable: false }
            ]
        };
    },

    components: {
        RolesForm
    },

    updated() {
        if (this.form.permission) {
            if (typeof (this.form.permission == "string")) {
                this.form.scope = this.form.permission.split([" "]);
            }
        }
    },

    computed: {
        ...mapState("crudx", ["data", "form"])
    },

    mounted() {
        this.index({ url: "api/role/index" });
    },

    methods: {
        ...mapActions("crudx", ["index", "edit", "update", "destroy"]),

        updateRole: async function() {
            if (this.$refs.roleForm.validate()) {
                let permission = "";
                for (let i = 0; i < this.form.scope.length; i++) {
                    permission = permission + this.form.scope[i] + " ";
                }
                this.form.permission = permission;
                await this.update({ url: "api/role/edit/" + this.form.id });
                this.index({ url: "api/role/index" });
                this.editRolesDialog = false;
            }
        },

        erase: async function() {
            await this.destroy({ url: "api/role/delete/" + this.roleID });
            this.index({ url: "api/role/index" });
            this.roleID = null;
            this.deleteRolesDialog = false;
        }
    }
};
</script>