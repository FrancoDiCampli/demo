import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import NotFound from "./views/NotFound.vue";

//_______________________VIEWS_______________________//

// Facturas Views
import FacturasIndex from "./views/facturas/index.vue";
import FacturasCreate from "./views/facturas/create.vue";

// Clientes Views
import ClientesIndex from "./views/clientes/index.vue";
import ClientesShow from "./views/clientes/show.vue";
import ClientesCreate from "./views/clientes/create.vue";

// Compras
import RemitosIndex from "./views/compras/index";
import RemitosCreate from "./views/compras/create";

// Proveedores
import ProveedoresIndex from "./views/proveedores/index.vue";
// import ProveedoresShow from "./views/proveedores/show.vue";
// import ProveedoresCreate from "./views/proveedores/create.vue";

// Productos Views
import ProductosIndex from "./views/productos/index.vue";
import ProductosShow from "./views/productos/show.vue";
import ProductosCreate from "./views/productos/create.vue";

// Reportes Views
import Reporte from "./views/reportes/Reporte.vue";

// Users Views
import Users from "./auth/views/Users.vue";

// Roles Views
import Roles from "./auth/views/Roles.vue";

// Auth Views
import Login from "./auth/views/Login.vue";
import Account from "./auth/views/Account.vue";

//_______________________VIEWS_______________________//

//____________________PERMISSIONS____________________//

const visitorOnly = [
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
];

const allUsers = [
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
];

const adminSuperAdmin = [
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
];

const superAdminOnly = [
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
];

//____________________PERMISSIONS____________________//

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

        // Ventas Routes
        {
            path: "/ventas",
            name: "ventas",
            component: FacturasIndex,
            meta: {
                permissions: allUsers
            }
        },

        {
            path: "/ventas/nueva",
            name: "nueva venta",
            component: FacturasCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Compras Routes
        {
            path: "/compras",
            name: "compras",
            component: RemitosIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/compras/nueva",
            name: "nueva compra",
            component: RemitosCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Clientes Routes
        {
            path: "/clientes",
            name: "clientes",
            component: ClientesIndex,
            meta: {
                permissions: allUsers
            }
        },

        {
            path: "/clientes/show/:id",
            name: "ver cliente",
            component: ClientesShow,
            props: true,
            meta: {
                permissions: allUsers
            }
        },

        {
            path: "/clientes/nuevo",
            name: "nuevo cliente",
            component: ClientesCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Proveedores Routes
        {
            path: "/proveedores",
            name: "proveedores",
            component: ProveedoresIndex,
            meta: {
                permissions: allUsers
            }
        },

        // {
        //     path: "/suppliers/show/:id",
        //     name: "ver supplier",
        //     component: ProveedoresShow,
        //     props: true,
        //     meta: {
        //         permissions: allUsers
        //     }
        // },

        // {
        //     path: "/suppliers/nuevo",
        //     name: "nuevo supplier",
        //     component: ProveedoresCreate,
        //     meta: {
        //         permissions: allUsers
        //     }
        // },

        // Productos Routes
        {
            path: "/productos",
            name: "productos",
            component: ProductosIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/productos/show/:id",
            name: "ver producto",
            component: ProductosShow,
            props: true,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/productos/nuevo",
            name: "nuevo producto",
            component: ProductosCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Reportes Routes
        {
            path: "/reporte",
            name: "reporte",
            component: Reporte,
            meta: {
                permissions: superAdminOnly
            }
        },

        // Users Routes
        {
            path: "/users",
            name: "users",
            component: Users,
            meta: {
                permissions: adminSuperAdmin
            }
        },

        // Roles Routes
        {
            path: "/roles",
            name: "roles",
            component: Roles,
            meta: {
                permissions: superAdminOnly
            }
        },

        // Auth Routes
        {
            path: "/login",
            name: "login",
            component: Login,
            meta: {
                permissions: visitorOnly
            }
        },
        {
            path: "/account",
            name: "account",
            component: Account,
            meta: {
                permissions: allUsers
            }
        }
    ]
});
