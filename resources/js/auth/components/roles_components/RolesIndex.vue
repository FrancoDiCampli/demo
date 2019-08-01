<template>
    <div v-if="roles != null">
        <!-- Roles Table -->
        <v-data-table :headers="headers" hide-actions :items="data" expand item-key="id">
            <template v-slot:items="rol">
                <tr>
                    <td
                        style="cursor: pointer;"
                        @click="rol.expanded = !rol.expanded"
                    >{{ rol.item.role }}</td>
                    <td
                        style="cursor: pointer;"
                        @click="rol.expanded = !rol.expanded"
                        class="tokens-description hidden-xs-only"
                    >{{ rol.item.description }}</td>
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
                </tr>
            </template>
            <template v-slot:expand="rol">
                <v-card flat>
                    <v-card-text>
                        <v-list two-line>
                            <template>
                                <v-subheader>Permisos</v-subheader>
                                <v-divider></v-divider>
                                <v-list-tile v-for="token in rol.item.tokens" :key="token.id">
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ token.description }}</v-list-tile-title>
                                        <v-list-tile-sub-title>{{ token.index }}</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </template>
                        </v-list>
                    </v-card-text>
                </v-card>
            </template>
        </v-data-table>
        <!-- Edit Roles Dialog -->
        <v-dialog v-model="editRolesDialog" width="500" persistent>
            <v-card>
                <v-card-text>
                    <h2>Editar Rol</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="roleForm" @submit.prevent="updateRole()">
                        <RolesForm></RolesForm>
                        <br />
                        <v-layout justify-end>
                            <v-btn
                                :disabled="inProcess"
                                @click="closeEdit()"
                                outline
                                color="primary"
                            >Cancelar</v-btn>
                            <v-btn
                                :loading="inProcess"
                                :disabled="inProcess"
                                type="submit"
                                color="primary"
                            >Editar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Delete Roles Dialog -->
        <v-dialog v-model="deleteRolesDialog" width="500" persistent>
            <v-card>
                <v-card-title>
                    <h2>¿Estás Seguro?</h2>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>¿Estás seguro que deseas eliminar este Rol? este cambio es irreversible</v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout justify-end wrap>
                        <v-btn
                            :disabled="inProcess"
                            @click="deleteRolesDialog = false;"
                            outline
                            color="primary"
                        >Cancelar</v-btn>
                        <v-btn
                            :loading="inProcess"
                            :disabled="inProcess"
                            @click="erase()"
                            color="primary"
                        >Eliminar</v-btn>
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
            roles: null,
            editRolesDialog: false,
            deleteRolesDialog: false,
            roleID: null,
            headers: [
                { text: "Rol", sortable: false },
                { text: "Permisos", class: "hidden-xs-only", sortable: false },
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
        ...mapState("crudx", ["data", "form", "showData", "inProcess"])
    },

    mounted() {
        this.getRoles();
    },

    methods: {
        ...mapActions("crudx", ["index", "edit", "update", "destroy"]),

        getRoles: async function() {
            let response = await this.index({ url: "/api/roles" });
            this.roles = response;
        },

        closeEdit: async function() {
            await this.getRoles();
            this.editRolesDialog = false;
        },

        updateRole: async function() {
            if (this.$refs.roleForm.validate()) {
                let permission = "";
                let description = "";
                for (let i = 0; i < this.form.scope.length; i++) {
                    let find = this.showData.find(
                        permiso => permiso.id === this.form.scope[i]
                    );

                    if (find) {
                        permission = permission + find.id + " ";
                        description = description + find.description + ", ";
                    }
                }

                this.form.permission = permission;
                this.form.description = description;
                await this.update({ url: "/api/roles/" + this.form.id });
                this.$refs.roleForm.reset();
                this.getRoles();
                this.editRolesDialog = false;
            }
        },

        erase: async function() {
            await this.destroy({ url: "/api/roles/" + this.roleID });
            this.getRoles();
            this.roleID = null;
            this.deleteRolesDialog = false;
        },

        log() {
            console.log(this.data);
        }
    }
};
</script>

<style>
.tokens-description {
    display: inline-block;
    margin-top: 26px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}

@media (min-width: 600px) {
    .tokens-description {
        max-width: 200px;
    }
}

@media (min-width: 960px) {
    .tokens-description {
        max-width: 400px;
    }
}
</style>
