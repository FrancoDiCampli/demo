<template>
    <div>
        <!-- Index configuracion -->
        <v-card-text>
            <ConfiguracionesIndex></ConfiguracionesIndex>
        </v-card-text>
    </div>
</template>

<script>
import ConfiguracionesIndex from "../../components/configuraciones/ConfiguracionesIndex.vue";
export default {
    name: "Configuraciones",

    components: {
        ConfiguracionesIndex
    }
};
</script>

<style>
</style>