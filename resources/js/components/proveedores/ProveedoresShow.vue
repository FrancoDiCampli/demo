<template>
    <div>
        <h1>Componente Show de Proveedores</h1>
        <ProveedoresShowData></ProveedoresShowData>
        <ProveedoresShowCompras></ProveedoresShowCompras>
    </div>
</template>

<script>
// Components
import ProveedoresShowData from "./ProveedoresShowData.vue";
import ProveedoresShowCompras from "./ProveedoresShowCompras.vue";

export default {
    name: "ProveedoresShow",

    components: {
        ProveedoresShowData,
        ProveedoresShowCompras
    }
};
</script>

<style>
</style>