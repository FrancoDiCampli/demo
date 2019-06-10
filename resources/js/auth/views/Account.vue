<template>
    <div>
        <br>
        <v-layout justify-center>
            <v-flex xs10 lg8>
                <v-layout justify-center>
                    <!-- Login/register card -->
                    <v-card width="400" height="350">
                        <v-card-text style="padding: 0;">
                            <v-layout wrap>
                                <!-- User View -->
                                <v-flex xs12 pa-2>
                                    <v-layout justify-end>
                                        <v-menu>
                                            <template v-slot:activator="{ on }">
                                                <v-btn flat icon dark color="primary" v-on="on">
                                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-tile
                                                    @click="editAccount(); editDialog = true"
                                                >
                                                    <v-list-tile-title>Editar Datos</v-list-tile-title>
                                                </v-list-tile>
                                                <v-list-tile @click="deleteDialog = true">
                                                    <v-list-tile-title>Eliminar mi Cuenta</v-list-tile-title>
                                                </v-list-tile>
                                            </v-list>
                                        </v-menu>
                                    </v-layout>
                                    <br>
                                    <br>
                                    <v-layout justify-center wrap>
                                        <v-flex xs12>
                                            <v-layout justify-center>
                                                <v-avatar class="profile" size="86">
                                                    <span class="display-1">{{ account.profile }}</span>
                                                </v-avatar>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12>
                                            <br>
                                            <h1
                                                class="text-xs-center primary--text"
                                            >{{ account.user.name }}</h1>
                                        </v-flex>
                                        <v-flex xs12>
                                            <h3
                                                class="text-xs-center primary--text"
                                            >{{ account.user.email }}</h3>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                    <!-- Edit Account Dialog -->
                    <v-dialog v-model="editDialog" width="400" persistent>
                        <v-card>
                            <v-form ref="edit_form" @submit.prevent="edit()">
                                <v-card-text>
                                    <h2>Editar mi Cuenta</h2>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <EditAccount></EditAccount>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-layout justify-end wrap>
                                        <v-btn
                                            @click="editDialog = false;"
                                            outline
                                            color="error"
                                        >Cancelar</v-btn>
                                        <v-btn
                                            type="submit"
                                            color="success"
                                            class="elevation-0"
                                        >Editar</v-btn>
                                    </v-layout>
                                </v-card-text>
                            </v-form>
                        </v-card>
                    </v-dialog>
                    <!-- Delete Account Dialog -->
                    <v-dialog v-model="deleteDialog" width="400" persistent>
                        <v-card>
                            <v-card-title>
                                <h2>¿Estás Seguro?</h2>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-text>¿Estás seguro que deseas eliminar tu Cuenta? este cambio es irreversible</v-card-text>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-layout justify-end wrap>
                                    <v-btn
                                        @click="deleteDialog = false;"
                                        outline
                                        color="success"
                                    >Cancelar</v-btn>
                                    <v-btn @click="erase()" color="error">Eliminar</v-btn>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import EditAccount from "../components/auth_components/EditAccount.vue";
import { mapState, mapGetters, mapActions } from "vuex";

export default {
    name: "Account",

    data() {
        return {
            editDialog: false,
            deleteDialog: false
        };
    },

    components: {
        EditAccount
    },

    computed: {
        ...mapGetters("auth", ["account"])
    },

    mounted() {
        this.getUser();
    },

    methods: {
        ...mapActions("auth", [
            "getUser",
            "editAccount",
            "updateAccount",
            "deleteAccount"
        ]),
        edit: async function() {
            if (this.$refs.edit_form.validate()) {
                await this.updateAccount();
                await this.getUser();
                this.editDialog = false;
            }
        },
        erase: async function() {
            await this.deleteAccount();
            this.$router.push("/");
        }
    }
};
</script>

<style>
.profile {
    border: solid 3px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.profile span {
    color: #26a69a;
}
</style>