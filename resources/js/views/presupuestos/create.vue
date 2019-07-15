<template>
    <div>
        <h1>Vista Create Presupuestos</h1>
        <PresupuestosForm></PresupuestosForm>
    </div>
</template>

<script>
// Components
import PresupuestosForm from "../../components/presupuestos/PresupuestosForm.vue";

export default {
    name: "PresupuestosCreate",

    components: {
        PresupuestosForm
    }
};
</script>

<style>
</style>
