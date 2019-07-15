<template>
    <div>
        <h1>Vista Edit de Facturas</h1>
        <FacturasForm></FacturasForm>
    </div>
</template>

<script>
// Components
import FacturasForm from "../../components/facturas/FacturasForm.vue";

export default {
    name: "editFactura",

    components: {
        FacturasForm
    }
};
</script>

<style>
</style>
