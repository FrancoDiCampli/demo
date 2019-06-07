<template>
    <div>
        <v-card>
            <br>
            <v-card-text>
                <v-layout justify-space-between>
                    <v-flex xs4 mx-1>
                        <v-text-field label="CUIT" box single-line></v-text-field>
                    </v-flex>
                    <v-flex xs4 mx-1>
                        <v-text-field label="Razón Social" box single-line></v-text-field>
                    </v-flex>
                    <v-flex xs4 mx-1>
                        <v-text-field label="Condición" box single-line></v-text-field>
                    </v-flex>
                </v-layout>
            </v-card-text>
            <v-expansion-panel>
                <v-expansion-panel-content>
                    <template v-slot:header>
                        <div>Más Detalles</div>
                    </template>
                    <v-card>
                        <v-card-text>
                            <p class="text-xs-center">Detalles</p>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <br>
            <v-card-text>
                <v-layout justify-space-between>
                    <v-flex xs3 mx-1>
                        <v-text-field label="Arituclo" box single-line></v-text-field>
                    </v-flex>
                    <v-flex xs3 mx-1>
                        <v-text-field label="Precio" box single-line></v-text-field>
                    </v-flex>
                    <v-flex xs3 mx-1>
                        <v-text-field label="Cantidad" box single-line></v-text-field>
                    </v-flex>
                    <v-flex xs3 mx-1>
                        <v-text-field label="Subtotal" box single-line></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout justify-center>
                    <v-btn color="primary" outline>Agregar</v-btn>
                </v-layout>
            </v-card-text>
            <v-card-text>
                <template>
                    <v-data-table :headers="headers" :items="articulos" hide-actions>
                        <template v-slot:items="articulo">
                            <td>{{ articulo.item.articulo }}</td>
                            <td>{{ articulo.item.precio }}</td>
                            <td>{{ articulo.item.cantidad }}</td>
                            <td>{{ articulo.item.subtotal }}</td>
                        </template>
                    </v-data-table>
                </template>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    name: "Factura",
    data() {
        return {
            headers: [
                { text: "Articulo", sortable: false },
                { text: "Precio", sortable: false },
                { text: "Cantidad", sortable: false },
                { text: "Subtotal", sortable: false }
            ],
            articulos: [
                {
                    articulo: "Manzana",
                    precio: "$10",
                    cantidad: "2",
                    subtotal: "$20"
                },
                {
                    articulo: "Pera",
                    precio: "$20",
                    cantidad: "3",
                    subtotal: "$60"
                },
                {
                    articulo: "Banana",
                    precio: "$15",
                    cantidad: "1",
                    subtotal: "$15"
                }
            ]
        };
    }
};
</script>
