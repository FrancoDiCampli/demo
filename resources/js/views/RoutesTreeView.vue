<template>
    <v-card>
        <v-toolbar card color="grey lighten-3">
            <v-icon>fas fa-clipboard-check</v-icon>
            <v-toolbar-title>Pendientes</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn flat @click="log()">log</v-btn>
        </v-toolbar>

        <v-layout>
            <v-flex>
                <v-card-text>
                    <v-treeview
                        v-model="selected"
                        :items="rutas"
                        activatable
                        active-class="grey lighten-4 primary--text"
                        selected-color="primary"
                        open-on-click
                        selectable
                        expand-icon="fas fa-chevron-down fa-xs vanished"
                        on-icon="fas fa-check-square fa-lg"
                        off-icon="far fa-square fa-lg"
                        indeterminate-icon="fas fa-minus-square fa-lg"
                    ></v-treeview>
                </v-card-text>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>
export default {
    name: "RoutesTreeView",

    data() {
        return {
            rutas: [
                {
                    id: 1,
                    name: "Ventas",
                    children: [
                        {
                            id: 11,
                            name: "index",
                            children: [{ id: 111, name: "FacturasIndex" }]
                        },
                        {
                            id: 12,
                            name: "create",
                            children: [{ id: 121, name: "FacturasForm" }]
                        },
                        {
                            id: 13,
                            name: "edit",
                            children: [{ id: 131, name: "FacturasForm" }]
                        }
                    ]
                },
                {
                    id: 2,
                    name: "Presupuestos",
                    children: [
                        {
                            id: 21,
                            name: "index",
                            children: [{ id: 211, name: "PresupuestosIndex" }]
                        },
                        {
                            id: 22,
                            name: "create",
                            children: [{ id: 221, name: "PresupuestosForm" }]
                        }
                    ]
                },
                {
                    id: 3,
                    name: "Clientes",
                    children: [
                        {
                            id: 31,
                            name: "index",
                            children: [{ id: 311, name: "ClientesIndex" }]
                        },
                        {
                            id: 32,
                            name: "create",
                            children: [{ id: 321, name: "ClientesForm" }]
                        },
                        {
                            id: 33,
                            name: "show",
                            children: [
                                {
                                    id: 331,
                                    name: "ClientesShow",
                                    children: [
                                        {
                                            id: 33311,
                                            name: "ClientesShowDataIterator"
                                        },
                                        {
                                            id: 33312,
                                            name: "ClientesShowFacturasTable"
                                        },
                                        {
                                            id: 33313,
                                            name: "ClientesShowCuentaTable"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    id: 4,
                    name: "Productos",
                    children: [
                        {
                            id: 41,
                            name: "index",
                            children: [{ id: 411, name: "ProductosIndex" }]
                        },
                        {
                            id: 42,
                            name: "create",
                            children: [{ id: 421, name: "ProductosForm" }]
                        },
                        {
                            id: 43,
                            name: "show",
                            children: [
                                {
                                    id: 431,
                                    name: "ProductosShow",
                                    children: [
                                        { id: 4311, name: "ProductosShowData" },
                                        {
                                            id: 4312,
                                            name: "ProductosShowInventario"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    id: 5,
                    name: "Proveedores",
                    children: [
                        {
                            id: 51,
                            name: "index",
                            children: [{ id: 511, name: "ProveedoresIndex" }]
                        },
                        {
                            id: 52,
                            name: "create",
                            children: [{ id: 521, name: "ProveedoresForm" }]
                        },
                        {
                            id: 53,
                            name: "show",
                            children: [
                                {
                                    id: 531,
                                    name: "ProveedoresShow",
                                    children: [
                                        {
                                            id: 5311,
                                            name: "ProveedoresShowData"
                                        },
                                        {
                                            id: 5312,
                                            name: "ProveedoresShowCompras"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    id: 6,
                    name: "Compras",
                    children: [
                        {
                            id: 61,
                            name: "index",
                            children: [{ id: 612, name: "RemitosIndex" }]
                        },
                        {
                            id: 62,
                            name: "create",
                            children: [{ id: 621, name: "RemitosForm" }]
                        }
                    ]
                },
                {
                    id: 7,
                    name: "Reportes",
                    children: [
                        {
                            id: 71,
                            name: "index",
                            children: [
                                { id: 711, name: "ReportesVentas" },
                                { id: 712, name: "ReportesCompras" },
                                { id: 713, name: "ReportesProductos" }
                            ]
                        }
                    ]
                }
            ],
            selected: [
                3,
                31,
                32,
                33,
                311,
                321,
                331,
                33311,
                33312,
                33313,
                211,
                21,
                431,
                4311,
                4312,
                43,
                42,
                421,
                411,
                41,
                4,
                411,
                51,
                7,
                71,
                711,
                712,
                713,
                121,
                12,
                111,
                11
            ]
        };
    },

    methods: {
        log() {
            console.log(this.selected);
        }
    }
};
</script>

<style>
.fa-chevron-down.vanished {
    opacity: 0;
}
</style>
