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
                <div v-if="notifications.length > 0">
                    <v-badge left color="error">
                        <template v-slot:badge>
                            <div v-if="notifications.length <= 99">
                                <span>{{ notifications.length }}</span>
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
            fixed
            hide-overlay
            temporary
        >
            <v-list dense>
                <div v-for="alert in notifications" :key="alert.iden">
                    <v-list-tile my-2 @click="goNotification(alert)">
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
                    title: "Presupuestos",
                    icon: "fas fa-file",
                    url: "/presupuestos",
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
                    title: "Compras",
                    icon: "fas fa-shopping-cart",
                    url: "/compras",
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
                    title: "Configuraciones",
                    icon: "fas fa-cog",
                    url: "/configuraciones",
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
    },
    computed: {
        ...mapState("auth", ["rol", "token"]),
        ...mapState("crudx", ["notifications"]),
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
        ...mapActions("crudx", ["index", "show"]),
        exit: async function() {
            await this.logout();
            this.$router.push("/");
            this.$user.set({ role: "visitor" });
            this.mini = true;
        },

        goNotification: async function(alert) {
            if (alert.type == "producto") {
                await this.show({ url: "/api/articulos/" + alert.id });
                this.$router.push("/productos/show/" + alert.id);
            } else if (alert.type == "cliente") {
                await this.show({ url: "/api/clientes/" + alert.id });
                this.$router.push("/clientes/show/" + alert.id);
            }
        }
    }
};
</script>

<style>
/* Estilos Personalizados:
    Los estilos establecidos dentro de esta etiqueta afectaran a todos los componentes
    siempre y cuando se usen las clases en los mismos.
    Esta implementación es contraria a la recomendada, pero debido a que los componentes 
    de Vuetify son externos no podemos utilizar la etiqueta Syle Scope en cada componente,
    como recomienda Vuejs, para establecer los estilos.
    Para evitar repitir las clases entre componentes provocando modificaciones no deseadas
    en los mismos, se estableceran todos los estilos de forma global en esta etiqueta.
*/

/* Estilos para el avatar de perfil */
.profile {
    border: solid 3px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.profile span {
    color: #26a69a;
}

/* Estilos para el avatar de perfil en el sidenav */
.profile-list {
    border: solid 1.5px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
    margin-top: 15px;
    cursor: pointer;
}
.profile-list span {
    color: #26a69a;
}

/* Estilos para el scrollbar */
body::-webkit-scrollbar {
    width: 7px;
}
body::-webkit-scrollbar-thumb {
    background-color: rgba(38, 166, 154, 0.75);
}

/* Estilo para el indicador de carga circular absoluto */
.loading {
    position: fixed;
    z-index: 999999;
    left: 47.3%;
    top: 44%;
}

/* Estilos para los inputs númericos */
input[type="number"] {
    -moz-appearance: textfield;
}
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

/* Estilos para los imputs con la primera letra mayuscula */
.capitalize input[type] {
    text-transform: capitalize;
}

/* Estilos para los campos de pago en las cuentas corrientes del cliente (ClientesShowCuentaTable) */
.input-pagos {
    width: 75px;
    display: block;
    margin-top: 8px;
    padding: 10px 0px;
    border: none;
    border-bottom: 1px solid #9e9e9e;
    transition: all 1s ease;
}

.input-pagos:focus {
    outline: none;
    border-bottom: 2px solid #26a69a;
    transition: all 0.5s ease;
}

.pagos tbody tr {
    border-bottom: none !important;
}

/* Estilos para indicar el tipo de comprobante */
.type-item {
    margin: 5px 0px 5px -12px;
    border: solid 1.5px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.type {
    margin-top: 15px;
    color: #26a69a;
}

/* Estilos para los search Table */
.search-table {
    border: solid 2px #26a69a;
    margin-top: -30px;
    border-top: none;
    margin-bottom: 20px;
    border-radius: 0px 0px 5px 5px;
}

.expansion-border {
    border-bottom: 1px solid #aaaaaa;
}

/* Estilos para la animacion de expanción en las Search Table */
.expand-transition {
    transition: all 0.5s ease;
}

.expand-enter,
.expand-leave {
    height: 0;
    opacity: 0;
}

/* Estilos para los Headers de factura */
.dataFactura {
    font-size: 12px;
    line-height: 5px;
    margin-top: 12px;
}

/* Estilos para los Headers de presupuesto */
.dataPresupuesto {
    font-size: 12px;
    line-height: 5px;
    margin-top: 12px;
}

/* Estilos para las esquinas de los cards de productos */
.tringle-right-button {
    position: relative;
    width: 70px;
    height: 70px;
    border-top: solid 35px #26a69a;
    border-right: solid 35px #26a69a;
    border-left: solid 35px transparent;
    border-bottom: solid 35px transparent;
    cursor: pointer;
}

.tringle-right-button .icon {
    position: absolute;
    margin-top: -22px;
    margin-left: 10px;
    color: white;
    font-size: 16px;
}

.tringle-left-button {
    position: relative;
    width: 50px;
    height: 50px;
    border-top: solid 25px transparent;
    border-right: solid 25px transparent;
    border-left: solid 25px;
    border-bottom: solid 25px;
}

.tringle-left-button .icon {
    position: absolute;
    margin-top: 2px;
    margin-left: -18px;
    color: white;
    font-size: 16px;
}

@media (min-width: 600px) {
    .tringle-right-button {
        width: 60px;
        height: 60px;
        border-top: solid 30px #26a69a;
        border-right: solid 30px #26a69a;
        border-left: solid 30px transparent;
        border-bottom: solid 30px transparent;
    }

    .tringle-right-button .icon {
        margin-top: -20px;
        margin-left: 8px;
    }

    .tringle-left-button {
        width: 60px;
        height: 60px;
        border-top: solid 30px transparent;
        border-right: solid 30px transparent;
        border-left: solid 30px;
        border-bottom: solid 30px;
    }

    .tringle-left-button .icon {
        margin-top: 4px;
        margin-left: -18px;
    }
}

@media (min-width: 1264px) {
    .tringle-right-button {
        width: 50px;
        height: 50px;
        border-top: solid 25px #26a69a;
        border-right: solid 25px #26a69a;
        border-left: solid 25px transparent;
        border-bottom: solid 25px transparent;
    }

    .tringle-right-button .icon {
        margin-top: -16px;
        margin-left: 8px;
        font-size: 14px;
    }
}
</style>