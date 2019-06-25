<template>
    <v-app>
        <!-- Navbar -->
        <v-toolbar color="secondary" class="elevation-0" v-show="token == null">
            <v-toolbar-title @click="$router.push('/')" style="cursor: pointer;">Gepetto</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn to="/login" flat v-show="token == null">Login</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <!-- Sidenav -->
        <v-navigation-drawer
            v-show="token !== null"
            v-model="drawer"
            :mini-variant="mini"
            hide-overlay
            stateless
            fixed
            class="hidden-xs-only"
        >
            <!-- Imagén de perfil y nombre de usuario -->
            <v-toolbar flat class="transparent">
                <v-list class="pa-0">
                    <v-list-tile avatar>
                        <v-avatar @click="mini = false" class="profile-list" size="50">
                            <span class="title">{{ account.profile }}</span>
                        </v-avatar>

                        <v-list-tile-content style="margin: 15px 0 0 15px;">
                            <v-list-tile-title class="primary--text">
                                <b>{{ account.user.name }}</b>
                            </v-list-tile-title>
                        </v-list-tile-content>

                        <v-list-tile-action style="margin-top: 15px;">
                            <v-btn icon @click.stop="mini = !mini" flat color="primary">
                                <v-icon>fas fa-angle-left</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
            </v-toolbar>

            <!-- Lita de acciones -->
            <v-list class="pt-0" dense>
                <br>
                <v-divider></v-divider>

                <!-- Acciones del vendedor -->
                <v-list-tile v-for="item in sellerItems" :key="item.title" :to="item.url">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-divider></v-divider>

                <!-- Acciones del admin -->
                <v-list-tile to="/users" v-show="rol == 'admin' || rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-user</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Usuarios</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <!-- Acciones del superAdmin -->
                <v-list-tile to="/roles" v-show="rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-tag</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Roles</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-divider></v-divider>

                <!-- Acciones de todos los usuarios -->
                <v-list-tile to="/account">
                    <v-list-tile-action>
                        <v-icon>fas fa-user-circle</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Mi cuenta</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="exit()">
                    <v-list-tile-action>
                        <v-icon>fas fa-sign-out-alt</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Cerrar Sesión</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>

        <!-- mobile -->
        <!-- Navbar Mobile -->
        <v-toolbar
            v-show="token !== null"
            color="primary"
            dark
            class="elevation-0 hidden hidden-sm-and-up"
        >
            <v-toolbar-items>
                <v-btn flat icon @click.stop="drawerMobile = !drawerMobile">
                    <v-icon>fas fa-bars</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-navigation-drawer v-model="drawerMobile" absolute temporary v-show="token !== null">
            <!-- Imagén de perfil y nombre de usuario -->
            <v-toolbar flat class="transparent">
                <v-list class="pa-0">
                    <v-list-tile avatar>
                        <v-avatar class="profile-list" size="50">
                            <span class="title">{{ account.profile }}</span>
                        </v-avatar>

                        <v-list-tile-content style="margin: 15px 0 0 15px;">
                            <v-list-tile-title class="primary--text">
                                <b>{{ account.user.name }}</b>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-toolbar>

            <!-- Lita de acciones -->
            <v-list class="pt-0" dense>
                <br>
                <v-divider></v-divider>

                <!-- Acciones del vendedor -->
                <v-list-tile v-for="item in sellerItems" :key="item.title" :to="item.url">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-divider></v-divider>

                <!-- Acciones del admin -->
                <v-list-tile to="/users" v-show="rol == 'admin' || rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-user</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Usuarios</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <!-- Acciones del superAdmin -->
                <v-list-tile to="/roles" v-show="rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-tag</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Roles</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-divider></v-divider>

                <!-- Acciones de todos los usuarios -->
                <v-list-tile to="/account">
                    <v-list-tile-action>
                        <v-icon>fas fa-user-circle</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Mi cuenta</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="exit()">
                    <v-list-tile-action>
                        <v-icon>fas fa-sign-out-alt</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>Cerrar Sesión</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>

        <br>

        <!-- Router view -->
        <v-content>
            <v-container>
                <v-layout :justify-center="mini ? true : false" :justify-end="mini ? false : true">
                    <v-flex xs12 sm10 lg8>
                        <router-view/>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";

export default {
    name: "App",

    data() {
        return {
            drawer: true,
            drawerMobile: false,
            sellerItems: [
                { title: "Ventas", icon: "fas fa-dollar-sign", url: "/ventas" },
                { title: "Clientes", icon: "fas fa-users", url: "/clientes" },
                {
                    title: "Productos",
                    icon: "fas fa-box-open",
                    url: "/productos"
                },
                {
                    title: "Cuenta",
                    icon: "fas fa-file-invoice-dollar",
                    url: "/cuenta"
                }
            ],
            right: null,
            mini: true
        };
    },

    mounted() {
        if (this.token !== null) {
            this.getUser();
        }
    },

    computed: {
        ...mapState("auth", ["rol", "token"]),
        ...mapGetters("auth", ["account"])
    },

    methods: {
        ...mapActions("auth", ["getUser", "logout"]),

        exit: async function() {
            await this.logout();
            this.$router.push("/");
            this.$user.set({ role: "visitor" });
            this.mini = true;
        }
    }
};
</script>

<style>
.profile-list {
    border: solid 1.5px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
    margin-top: 15px;
    cursor: pointer;
}

.profile-list span {
    color: #26a69a;
}

body::-webkit-scrollbar {
    width: 7px;
}

body::-webkit-scrollbar-thumb {
    background-color: rgba(38, 166, 154, 0.75);
}
</style>
