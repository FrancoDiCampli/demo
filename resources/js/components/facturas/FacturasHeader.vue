<template>
    <div>
        <v-layout wrap row>
            <v-flex sm6>
                <div class="companyName">
                    <h1><b>Controller</b></h1>
                    <h3><b>Programación y Diseño</b></h3>
                </div>
                <br>
                <div class="companyData">
                    <p>Dirección 123</p>
                    <p>CEL: 3735527874</p>
                    <p>3540 VILLA-ÁNGELA CHACO</p>
                </div>
            </v-flex>
            <v-divider vertical></v-divider>
            <v-flex>
                <div class="companyName">
                    <br>
                    <h1><b>Cuit: </b>20417590200</h1>
                    <br>
                    <h3><b>Fecha: </b>26/04/2019</h3>
                    <h4><b>Fecha de inicio de Actividades: </b>26/04/19</h4>
                </div>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>

    export default {

        name: 'FacturasHeader' 

    }

</script>

<style scoped>

.companyName {
    text-align: center;
}

.companyData {
    text-align: center;
    line-height: 5px;
}

</style>