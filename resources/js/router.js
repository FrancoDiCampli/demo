import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import NotFound from "./views/NotFound.vue";
import RoutesTree from "./views/RoutesTreeView.vue";

//_______________________VIEWS_______________________//

// Ventas Views
import FacturasIndex from "./views/ventas/index.vue";
import FacturasCreate from "./views/ventas/create.vue";
import FacturasEdit from "./views/ventas/edit.vue";

//Presupuestos Views
import PresupuestosIndex from "./views/presupuestos/index.vue";
import PresupuestosCreate from "./views/presupuestos/create.vue";

// Clientes Views
import ClientesIndex from "./views/clientes/index.vue";
import ClientesCreate from "./views/clientes/create.vue";
import ClientesShow from "./views/clientes/show.vue";

// Productos Views
import ProductosIndex from "./views/productos/index.vue";
import ProductosCreate from "./views/productos/create.vue";
import ProductosShow from "./views/productos/show.vue";

// Proveedores Views
import ProveedoresIndex from "./views/proveedores/index.vue";
import ProveedoresCreate from "./views/proveedores/create.vue";
import ProveedoresShow from "./views/proveedores/show.vue";

// Compras Views
import ComprasIndex from "./views/compras/index.vue";
import ComprasCreate from "./views/compras/create.vue";

// Reportes Views
import ReportesIndex from "./views/reportes/index.vue";

// Users Views
import Users from "./auth/views/Users.vue";

// Roles Views
import Roles from "./auth/views/Roles.vue";

// Auth Views
import Login from "./auth/views/Login.vue";
import Account from "./auth/views/Account.vue";

// Configuraciones Views
import ConfiguracionesIndex from "./views/configuraciones/index.vue";

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
        {
            path: "/routesTree",
            name: "routes tree",
            component: RoutesTree,
            meta: {
                permissions: superAdminOnly
            }
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
        {
            path: "/ventas/edit",
            name: "editar venta",
            component: FacturasEdit,
            meta: {
                permissions: allUsers
            }
        },

        // Presupuestos Routes
        {
            path: "/presupuestos",
            name: "presupuestos",
            component: PresupuestosIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/presupuestos/nuevo",
            name: "nueva presupuesto",
            component: PresupuestosCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Compras Routes
        {
            path: "/compras",
            name: "compras",
            component: ComprasIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/compras/nueva",
            name: "nueva compra",
            component: ComprasCreate,
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
            path: "/clientes/nuevo",
            name: "nuevo cliente",
            component: ClientesCreate,
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
            path: "/productos/nuevo",
            name: "nuevo producto",
            component: ProductosCreate,
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

        // Proveedores Routes
        {
            path: "/proveedores",
            name: "proveedores",
            component: ProveedoresIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/proveedores/nuevo",
            name: "nuevo proveedor",
            component: ProveedoresCreate,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/proveedores/show/:id",
            name: "ver proveedor",
            component: ProveedoresShow,
            props: true,
            meta: {
                permissions: allUsers
            }
        },

        // Compras Routes
        {
            path: "/compras",
            name: "compras",
            component: ComprasIndex,
            meta: {
                permissions: allUsers
            }
        },
        {
            path: "/compras/nuevo",
            name: "nueva compra",
            component: ComprasCreate,
            meta: {
                permissions: allUsers
            }
        },

        // Reportes Routes
        {
            path: "/reportes",
            name: "reportes",
            component: ReportesIndex,
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
        // Configuraciones
        {
            path: "/configuraciones",
            name: "configuraciones",
            component: ConfiguracionesIndex,
            meta: {
                permissions: superAdminOnly
            }
        },

        // Cuenta
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
