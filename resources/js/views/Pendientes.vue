<template>
    <div>
        <v-layout>
            <v-flex xs12 px-3>
                <v-card>
                    <v-card-title>
                        <h2>Pendientes</h2>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <br />
        <v-layout justify-space-between>
            <v-flex xs12 sm6 px-3>
                <v-expansion-panel>
                    <v-expansion-panel-content expand-icon="fas fa-caret-down" class="elevation-0">
                        <template v-slot:header>
                            <div>
                                <h3 class="error--text">Prioridad: URGENTE</h3>
                            </div>
                        </template>
                        <v-card>
                            <v-card-text>
                                <div v-for="urgente in pendientesUrgente" :key="urgente.label">
                                    <v-checkbox
                                        style="padding: 0px; margin: 0px;"
                                        color="error"
                                        readonly
                                        :label="urgente.label"
                                        v-model="urgente.value"
                                    ></v-checkbox>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                    <v-expansion-panel-content expand-icon="fas fa-caret-down" class="elevation-0">
                        <template v-slot:header>
                            <div>
                                <h3 class="blue--text">Prioridad: ALTA</h3>
                            </div>
                        </template>
                        <v-card>
                            <v-card-text>
                                <div v-for="urgente in pendientesImportantes" :key="urgente.label">
                                    <v-checkbox
                                        style="padding: 0px; margin: 0px;"
                                        color="blue"
                                        readonly
                                        :label="urgente.label"
                                        v-model="urgente.value"
                                    ></v-checkbox>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                    <v-expansion-panel-content expand-icon="fas fa-caret-down" class="elevation-0">
                        <template v-slot:header>
                            <div>
                                <h3 class="amber--text">Prioridad: BAJA</h3>
                            </div>
                        </template>
                        <v-card>
                            <v-card-text>
                                <div v-for="urgente in pendientesBajo" :key="urgente.label">
                                    <v-checkbox
                                        style="padding: 0px; margin: 0px;"
                                        color="amber"
                                        readonly
                                        :label="urgente.label"
                                        v-model="urgente.value"
                                    ></v-checkbox>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                    <v-expansion-panel-content expand-icon="fas fa-caret-down" class="elevation-0">
                        <template v-slot:header>
                            <div>
                                <h3 class="green--text">Prioridad: MUY BAJA</h3>
                            </div>
                        </template>
                        <v-card>
                            <v-card-text>
                                <div v-for="urgente in pendientesNulo" :key="urgente.label">
                                    <v-checkbox
                                        style="padding: 0px; margin: 0px;"
                                        color="green"
                                        readonly
                                        :label="urgente.label"
                                        v-model="urgente.value"
                                    ></v-checkbox>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-card>
                    <v-card-text>
                        <ve-pie :data="pendientesChartData"></ve-pie>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
export default {
    name: "Pendientes.vue",

    data() {
        return {
            pendientesUrgente: [
                {
                    label: "Validación de Stock en el detalles de facturas",
                    value: false
                },
                {
                    label: "Valido hasta en presupuestos PDF",
                    value: false
                },
                {
                    label: "Corregir PDF pagos",
                    value: false
                },
                {
                    label: "Nuevo tab recibos",
                    value: false
                },
                {
                    label: "Botón imprimir recibo",
                    value: false
                },
                {
                    label: "Resetear variable totol a pagar",
                    value: false
                }
            ],
            pendientesImportantes: [
                {
                    label: "Proveedor Consumidor Final seeder",
                    value: false
                },
                {
                    label:
                        "Productos codigo proveedor editar si no tiene inventarios",
                    value: false
                }
            ],
            pendientesBajo: [
                {
                    label: "Revisar PDF compras nombres largos",
                    value: false
                },
                {
                    label: "Provincia localidad y codigo postal precargados",
                    value: false
                },
                {
                    label: "Controladores Verificar limite Index",
                    value: false
                },
                {
                    label: "Show Soft Deletes",
                    value: false
                },
                {
                    label: "Botón de notificaciones FIX",
                    value: false
                }
            ],
            pendientesNulo: [
                {
                    label: "Select Provincias localidad",
                    value: false
                },
                {
                    label: "Comprobacion certificados AFIP",
                    value: false
                }
            ],
            pendientesChartData: {
                columns: ["prioridad", "cantidadTareas"],
                rows: [
                    { prioridad: "URGENTE", cantidadTareas: 6 },
                    { prioridad: "ALTA", cantidadTareas: 2 },
                    { prioridad: "BAJA", cantidadTareas: 5 },
                    { prioridad: "MUY BAJA", cantidadTareas: 2 }
                ]
            }
        };
    }
};
</script>

<style>
</style>
