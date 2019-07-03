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

// Productos Views
import ProductosIndex from "./views/productos/index.vue";
import ProductosShow from "./views/productos/show.vue";
import ProductosCreate from "./views/productos/create.vue";

// Reportes
import Reporte from "./views/Reporte.vue";

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

// ELIMINAR _____________________________________
// Cuenta Corriente
import Cuenta from "./views/Cuenta.vue";

// Inventarios
import Inventario from "./views/Inventario.vue";

// Remitos
import Remito from "./views/Remito.vue";
import RemitoNew from "./views/RemitoNew.vue";
// ELIMINAR _____________________________________

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

        // Facturas Routes
        {
            path: "/facturas",
            name: "facturas",
            component: FacturasIndex,
            meta: {
                permissions: allUsers
            }
        },

        {
            path: "/facturas/nueva",
            name: "nueva factura",
            component: FacturasCreate,
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
            path: "/productos/show",
            name: "ver producto",
            component: ProductosShow,
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
                permissions: allUsers
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
        },

        // ELIMINAR _____________________________________

        //Cuentas Routes
        {
            path: "/cuenta",
            name: "cuenta",
            component: Cuenta,
            meta: {
                permissions: allUsers
            }
        },

        //Reportes Routes
        {
            path: "/reporte",
            name: "reporte",
            component: Reporte,
            meta: {
                permissions: allUsers
            }
        },

        //Inventario Routes
        {
            path: "/inventario",
            name: "inventario",
            component: Inventario,
            meta: {
                permissions: allUsers
            }
        },

        //Remitos Routes
        {
            path: "/remito",
            name: "remito",
            component: Remito,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/compras/nueva",
            name: "RemitoNew",
            component: RemitoNew,
            meta: {
                permissions: allUsers
            }
        }
        // ELIMINAR _____________________________________
    ]
});
