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
                    @click="createRolesDialog = !createRolesDialog"
                    color="primary"
                    v-on="on"
                >
                    <v-icon>fas fa-plus</v-icon>
                </v-btn>
            </template>
            <span>Nuevo Rol</span>
        </v-tooltip>

        <v-dialog v-model="createRolesDialog" width="500" persistent>
            <v-card>
                <v-card-text>
                    <h2>Nuevo Rol</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="roleForm" @submit.prevent="saveRole">
                        <RolesForm></RolesForm>
                        <br />
                        <v-layout justify-end>
                            <v-btn
                                :disabled="inProcess"
                                @click="createRolesDialog = false"
                                outline
                                color="primary"
                            >Cancelar</v-btn>
                            <v-btn
                                :loading="inProcess"
                                :disabled="inProcess"
                                class="elevation-0"
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
                <RolesIndex></RolesIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

import RolesIndex from "../components/roles_components/RolesIndex.vue";
import RolesForm from "../components/roles_components/RolesForm.vue";

export default {
    name: "Roles",

    data() {
        return {
            createRolesDialog: false
        };
    },

    components: {
        RolesIndex,
        RolesForm
    },

    computed: {
        ...mapState("crudx", ["form", "showData", "inProcess"])
    },

    methods: {
        ...mapActions("crudx", ["index", "save"]),

        saveRole: async function() {
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
                await this.save({ url: "/api/roles" });
                this.$refs.roleForm.reset();
                this.index({ url: "/api/roles" });
                this.createRolesDialog = false;
            }
        }
    }
};
</script>