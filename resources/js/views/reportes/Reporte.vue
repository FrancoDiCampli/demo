<template>
    <div>
        <!-- Listado de ventas-->
        <v-card>
            <v-card-text>
                <ReporteIndex></ReporteIndex>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

// Components
import ReporteIndex from "../../components/reportes/ReporteIndex.vue";

export default {
    name: "Reporte",

    data() {
        return {};
    },
    components: {
        ReporteIndex
    }
};
</script>

<style>
</style>
