<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 px-3>
                <ve-line :data="ventas.ventasFechaChart"></ve-line>
            </v-flex>
        </v-layout>

        <v-layout justify-space-around>
            <v-flex xs12 px-3>
                <v-data-table hide-actions :headers="headers" :items="ventas.ventasFecha">
                    <template v-slot:items="factura">
                        <td class="hidden-xs-only">
                            <v-avatar class="type-item">
                                <p class="title type">{{ factura.item.letracomprobante }}</p>
                            </v-avatar>
                        </td>
                        <td>
                            <div
                                v-if="factura.item.comprobanteafip != null"
                            >{{ factura.item.comprobanteafip }}</div>
                            <div v-else>{{ factura.item.id }}</div>
                        </td>
                        <td>{{ factura.item.total }}</td>
                        <td class="hidden-xs-only">{{ factura.item.condicionventa }}</td>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
export default {
    name: "ReporteVentas",

    props: ["ventas"],

    data() {
        return {
            headers: [
                { text: "Tipo", sortable: false, class: "hidden-xs-only" },
                { text: "Nº Factura", sortable: false },
                { text: "Importe", sortable: false },
                { text: "Condición", sortable: false, class: "hidden-xs-only" }
            ]
        };
    }
};
</script>

<style>
</style>

