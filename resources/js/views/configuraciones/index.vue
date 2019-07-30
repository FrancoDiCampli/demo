<template>
    <div>
        <v-layout justify-center>
            <ConfiguracionesStandard></ConfiguracionesStandard>
        </v-layout>
        <v-layout justify-center>
            <v-btn
                v-show="!avanzada"
                @click="avanzada = true"
                color="primary"
                flat
            >Configuración Avanzada</v-btn>
        </v-layout>
        <v-layout justify-center v-show="avanzada">
            <ConfiguracionesAvanzada></ConfiguracionesAvanzada>
        </v-layout>
    </div>
</template>

<script>
// Components
import ConfiguracionesStandard from "../../components/configuraciones/ConfiguracionesStandard.vue";
import ConfiguracionesAvanzada from "../../components/configuraciones/ConfiguracionesAvanzada.vue";

export default {
    name: "Configuraciones",

    data() {
        return {
            avanzada: false
        };
    },

    components: {
        ConfiguracionesStandard,
        ConfiguracionesAvanzada
    }
};
</script>

<style>
</style>