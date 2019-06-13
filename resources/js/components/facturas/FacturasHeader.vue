<template>
    <div>
        <v-card-text>
            <v-layout wrap row>
                <v-flex sm6>
                    <div class="companyName">
                        <h1>
                            <b>Controller</b>
                        </h1>
                    </div>
                    <br>
                    <br>
                    <div class="companyData">
                        <p>
                            <b>Razón Social:</b> Karla Paola Sabrina Rafel
                        </p>
                        <p>
                            <b>Domicilio Comercial:</b> Hungría 165
                        </p>
                        <p>
                            <b>Condición Frente al IVA:</b> Responsable Monotributo
                        </p>
                    </div>
                </v-flex>
                <v-divider vertical></v-divider>
                <v-flex>
                    <div class="companyName">
                        <h1>
                            <b>Factura</b>
                        </h1>
                    </div>
                    <br>
                    <br>
                    <div class="clientData">
                        <p>
                            <b>Punto de Venta:</b> 0003
                            <b>Comprobante Nº:</b> 2
                        </p>
                        <p>
                            <b>Fecha de Emisión:</b> 29/05/2019
                        </p>
                        <p>
                            <b>Cuit:</b> 27265936178
                        </p>
                        <p>
                            <b>Ingresos Brutos:</b> 27265936178
                        </p>
                        <p>
                            <b>Inicio de Actividades:</b> 01-04-2012
                        </p>
                    </div>
                </v-flex>
            </v-layout>
            <v-divider></v-divider>
        </v-card-text>
    </div>
</template>

<script>
export default {
    name: "FacturasHeader"
};
</script>

<style scoped>
.companyName {
    text-align: center;
}

.companyData {
    font-size: 12px;
    text-align: left;
    line-height: 5px;
    margin-left: 15px;
}

.clientData {
    font-size: 12px;
    text-align: right;
    line-height: 5px;
    margin: 0px 15px 0px 0px;
}
</style>