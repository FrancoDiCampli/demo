<template>
    <div>
        <v-layout justify-space-around>
            <v-flex xs12 px-3>
                <ve-line :data="compras.comprasFechaChart"></ve-line>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
export default {
    name: "ReporteCompras",

    props: ["compras"],

    data() {
        return {};
    }
};
</script>

<style>
</style>
