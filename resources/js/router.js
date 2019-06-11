import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import NotFound from "./views/NotFound.vue";

//Auth Views
import Login from "./auth/views/Login.vue";
import Account from "./auth/views/Account.vue";

//Roles Views
import Roles from "./auth/views/Roles.vue";

//Users Views
import Users from "./auth/views/Users.vue";

//Clientes Views
import Cliente from "./views/Cliente.vue";

//Facturas Views
import Factura from "./views/Factura.vue";
import FacturaNew from "./views/FacturaNew.vue";

//Productos Views
import Productos from "./views/Productos.vue";

Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "*",
            component: NotFound
        },

        //Auth Routes
        {
            path: "/login",
            name: "login",
            component: Login,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: true
                    },
                    {
                        role: "superAdmin",
                        access: false,
                        redirect: "account"
                    },
                    {
                        role: "admin",
                        access: false,
                        redirect: "account"
                    },
                    {
                        role: "seller",
                        access: false,
                        redirect: "account"
                    }
                ]
            }
        },
        {
            path: "/account",
            name: "account",
            component: Account,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: true
                    }
                ]
            }
        },

        //Roles Routes
        {
            path: "/roles",
            name: "roles",
            component: Roles,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: false,
                        redirect: "account"
                    },
                    {
                        role: "seller",
                        access: false,
                        redirect: "account"
                    }
                ]
            }
        },

        //Users Routes
        {
            path: "/users",
            name: "users",
            component: Users,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: false,
                        redirect: "account"
                    }
                ]
            }
        },

        //Clientes Routes
        {
            path: "/clientes",
            name: "clientes",
            component: Cliente,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: true
                    }
                ]
            }
        },

        //Facturas Routes
        {
            path: "/ventas",
            name: "ventas",
            component: Factura,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: true
                    }
                ]
            }
        },

        {
            path: "/ventas/nueva",
            name: "nuevaVenta",
            component: FacturaNew,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: true
                    }
                ]
            }
        },

        //Productos Routes
        {
            path: "/productos",
            name: "productos",
            component: Productos,
            meta: {
                permissions: [
                    {
                        role: "visitor",
                        access: false,
                        redirect: "login"
                    },
                    {
                        role: "superAdmin",
                        access: true
                    },
                    {
                        role: "admin",
                        access: true
                    },
                    {
                        role: "seller",
                        access: true
                    }
                ]
            }
        }
    ]
});
