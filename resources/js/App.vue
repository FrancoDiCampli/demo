<template>
    <v-app>
        <!-- Notificaciones -->

        <!-- Navbar Inicial (Solo visible antes de iniciar sesión) -->
        <v-toolbar color="secondary" class="elevation-0" v-show="token == null">
            <v-toolbar-title @click="$router.push('/')" style="cursor: pointer;">Gepetto</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn to="/login" flat v-show="token == null">Login</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>

        <v-toolbar
            v-show="token !== null"
            :color="screenWidth <= 600 ? 'primary' : 'transparent'"
            :absolute="screenWidth <= 600 ? false : true"
            dark
            class="elevation-0"
        >
            <v-btn class="hidden-sm-and-up" flat icon @click.stop="mobileDrawer = !mobileDrawer">
                <v-icon>fas fa-bars</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn flat icon @click="notificationDrawer = !notificationDrawer">
                <div v-if="alerts.length > 0">
                    <v-badge left color="error">
                        <template v-slot:badge>
                            <div v-if="alerts.length <= 99">
                                <span>{{ alerts.length }}</span>
                            </div>
                            <div v-else>
                                <span>99+</span>
                            </div>
                        </template>
                        <v-icon :color="screenWidth <= 600 ? 'white' : 'primary'">fas fa-bell</v-icon>
                    </v-badge>
                </div>
                <div v-else>
                    <v-icon :color="screenWidth <= 600 ? 'white' : 'primary'">fas fa-bell</v-icon>
                </div>
            </v-btn>
        </v-toolbar>

        <!-- Sidenav Notificaciones -->
        <v-navigation-drawer
            v-show="token !== null"
            v-model="notificationDrawer"
            right
            absolute
            temporary
        >
            <v-list dense>
                <div v-for="alert in alerts" :key="alert.iden">
                    <v-list-tile my-2 @click="$router.push(alert.url)">
                        <v-list-tile-action>
                            <v-icon :color="alert.color">{{ alert.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title :class="alert.color+'--text'">{{ alert.msg }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </div>
            </v-list>
        </v-navigation-drawer>

        <!-- Sidenav Principal -->
        <v-navigation-drawer
            v-show="token !== null"
            v-model="drawer"
            :mini-variant="screenWidth > 600 ? mini : false"
            :hide-overlay="screenWidth > 600"
            :stateless="screenWidth > 600"
            :fixed="screenWidth > 600"
            :absolute="screenWidth <= 600"
            :temporary="screenWidth <= 600"
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

                        <v-list-tile-action style="margin-top: 15px;" v-show="screenWidth > 600">
                            <v-btn icon @click.stop="mini = !mini" flat color="primary">
                                <v-icon>fas fa-angle-left</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
            </v-toolbar>

            <!-- Lita de acciones -->
            <v-list class="pt-0" dense>
                <br />
                <v-divider></v-divider>

                <!-- Acciones del vendedor -->
                <div v-for="item in sellerItems" :key="item.title">
                    <div
                        v-show="
                        item.rol == 'seller' && rol == 'seller' ||
                        item.rol == 'seller' && rol == 'admin' ||
                        item.rol == 'seller' && rol == 'superAdmin'
                    "
                    >
                        <v-list-tile :to="item.url">
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-divider v-show="item.divider"></v-divider>
                    </div>
                    <div
                        v-show="item.rol == 'admin' && rol == 'admin' ||
                        item.rol == 'admin' && rol == 'superAdmin'
                    "
                    >
                        <v-list-tile :to="item.url">
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-divider v-show="item.divider"></v-divider>
                    </div>
                    <div v-show="item.rol == 'superAdmin' && rol == 'superAdmin'">
                        <v-list-tile :to="item.url">
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-divider v-show="item.divider"></v-divider>
                    </div>
                </div>

                <!-- <v-list-tile to="/inventario" v-show="rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-boxes</v-icon>
                    </v-list-tile-action>
                </v-list-tile>
                <v-divider></v-divider>

                <v-list-tile to="/remito" v-show="rol == 'superAdmin'">
                    <v-list-tile-action>
                        <v-icon>fas fa-money-check</v-icon>
                    </v-list-tile-action>
                </v-list-tile>
                <v-divider></v-divider>-->

                <!-- Cerrar Sesión -->
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

        <br />

        <!-- Router view -->
        <v-content>
            <v-container>
                <v-layout :justify-center="mini ? true : false" :justify-end="mini ? false : true">
                    <v-flex xs12 sm10 lg8>
                        <router-view />
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
// Axios
import axios from "axios";

// Vuex
import { mapState, mapActions, mapGetters } from "vuex";

export default {
    name: "App",
    data() {
        return {
            mobileDrawer: false,
            notificationDrawer: false,
            sellerItems: [
                {
                    title: "Ventas",
                    icon: "fas fa-dollar-sign",
                    url: "/ventas",
                    divider: false,
                    rol: "seller"
                },
                {
                    title: "Clientes",
                    icon: "fas fa-users",
                    url: "/clientes",
                    divider: false,
                    rol: "seller"
                },
                {
                    title: "Productos",
                    icon: "fas fa-box-open",
                    url: "/productos",
                    divider: false,
                    rol: "seller"
                },
                {
                    title: "Proveedores",
                    icon: "fas fa-truck-moving",
                    url: "/proveedores",
                    divider: false,
                    rol: "seller"
                },
                {
                    title: "Reportes",
                    icon: "fas fa-clipboard",
                    url: "/reporte",
                    divider: true,
                    rol: "seller"
                },
                {
                    title: "Usuarios",
                    icon: "fas fa-address-card",
                    url: "/users",
                    divider: false,
                    rol: "admin"
                },
                {
                    title: "Roles",
                    icon: "fas fa-tag",
                    url: "/roles",
                    divider: true,
                    rol: "superAdmin"
                },
                {
                    title: "Mi Cuenta",
                    icon: "fas fa-user-circle",
                    url: "/account",
                    divider: false,
                    rol: null
                }
            ],
            right: null,
            mini: true,
            alerts: []
        };
    },

    mounted() {
        if (this.token !== null) {
            this.getUser();
        }

        this.getAlerts();
    },
    computed: {
        ...mapState("auth", ["rol", "token"]),
        ...mapGetters("auth", ["account"]),

        screenWidth() {
            return window.innerWidth;
        },

        drawer: {
            set() {},
            get() {
                if (window.innerWidth <= 600) {
                    return this.mobileDrawer;
                } else {
                    return true;
                }
            }
        }
    },
    methods: {
        ...mapActions("auth", ["getUser", "logout"]),
        ...mapActions("crudx", ["index"]),
        exit: async function() {
            await this.logout();
            this.$router.push("/");
            this.$user.set({ role: "visitor" });
            this.mini = true;
        },

        getAlerts: async function() {
            axios
                .get("/api/notifications")
                .then(response => {
                    this.alerts = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
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