<template>
    <div>
        <v-layout justify-center>
            <br>
            <h1>
                <b>Cuenta Corriente</b>
            </h1>
        </v-layout>
    </div>
</template>

<script>
import { mapState } from 'vuex';
export default {
    name: "ClientesShowCuentaTable",

    computed:{
        ...mapState('crudx',['showData'])
    },

    mounted(){
        console.log(this.showData);
    },
};
</script>
