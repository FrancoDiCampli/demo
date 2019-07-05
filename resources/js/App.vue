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
                <v-badge left color="error">
                    <template v-slot:badge>
                        <span>15</span>
                    </template>
                    <v-icon :color="screenWidth <= 600 ? 'white' : 'primary'">fas fa-bell</v-icon>
                </v-badge>
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
            <v-toolbar flat>
                <v-list>
                    <v-list-tile>
                        <v-list-tile-title class="title">Notificaciones</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-toolbar>

            <v-divider></v-divider>

            <v-list dense class="pt-0">
                <v-list-tile v-for="alert in alerts" :key="alert.id">
                    <v-list-tile-content>
                        <v-list-tile-title>{{ alert.articulo }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
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
                    url: "/facturas",
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
                    title: "Reportes",
                    icon: "fas fa-clipboard",
                    url: "/reporte",
                    divider: true,
                    rol: "seller"
                },
                {
                    title: "Usuarios",
                    icon: "fas fa-users",
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
                .get("/api/articulos")
                .then(res => {
                    let response = res.data;
                    console.log(response);
                    if (response.articulos.length) {
                        for (let i = 0; i < response.articulos.length; i++) {
                            if (response.articulos[i].stock.length <= 0) {
                                let articulo = {
                                    id: response.articulos[i].id,
                                    articulo: response.articulos[i].articulo,
                                    msg: "necesita reposición",
                                    icon: "fas fa-exclamation",
                                    color: "error"
                                };

                                this.alerts.push(articulo);
                            } else {
                                if (response.articulos[i].stock[0].total == 0) {
                                    let articulo = {
                                        id: response.articulos[i].id,
                                        articulo:
                                            response.articulos[i].articulo,
                                        msg: "necesita reposición",
                                        icon: "fas fa-exclamation",
                                        color: "error"
                                    };

                                    this.alerts.push(articulo);
                                } else if (
                                    response.articulos[i].stock[0].total <=
                                    response.articulos[i].stockminimo
                                ) {
                                    let articulo = {
                                        id: response.articulos[i].id,
                                        articulo:
                                            response.articulos[i].articulo,
                                        msg: "no posee suficiente stock",
                                        icon: "fas fa-clock",
                                        color: "warning"
                                    };

                                    this.alerts.push(articulo);
                                }
                            }
                        }
                    }
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