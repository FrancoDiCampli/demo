<template>
    <div>
        <FacturasHeader></FacturasHeader>
        <FacturasClientes></FacturasClientes>
    </div>
</template>

<script>
//Components
import FacturasHeader from "./FacturasHeader.vue";
import FacturasClientes from "./FacturasClientes.vue";

export default {
    name: "FacturasForm",

    components: {
        FacturasHeader,
        FacturasClientes
    }
};
</script>

<style>
</style>
