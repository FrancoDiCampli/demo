<template>
    <div>
        <v-form ref="formFactura" @submit.prevent="saveFactura">
            <FacturasHeader></FacturasHeader>
            <br>
            <FacturasClientes></FacturasClientes>
            <FacturasArticulos></FacturasArticulos>
            <v-btn type="submit" color="primary">Guardar</v-btn>
        </v-form>
    </div>
</template>

<script>
//Components
import FacturasHeader from "./FacturasHeader.vue";
import FacturasClientes from "./FacturasClientes.vue";
import FacturasArticulos from "./FacturasArticulos.vue";
import { mapState, mapActions } from "vuex";

export default {
    name: "FacturasForm",

    components: {
        FacturasHeader,
        FacturasClientes,
        FacturasArticulos
    },

    computed: {
        ...mapState("crudx", ["form"])
    },

    methods: {
        ...mapActions("crudx", ["save"]),

        saveFactura() {
            this.save({ url: "/api/facturas" });
        }
    }
};
</script>

<style>
</style>
