<template>
    <v-layout justify-space-around wrap>
        <v-flex xs12 sm6>
            <v-layout justify-center wrap>
                <v-flex xs12>
                    <v-layout justify-center>
                        <croppa
                            v-model="imgProducto"
                            :prevent-white-space="true"
                            :show-remove-button="false"
                            :width="250"
                            :height="250"
                        ></croppa>
                    </v-layout>
                </v-flex>
                <v-flex xs12>
                    <v-layout justify-center>
                        <v-btn flat icon color="primary" @click="imgProducto.zoomIn()">
                            <v-icon>fas fa-search-plus</v-icon>
                        </v-btn>
                        <v-btn flat icon color="primary" @click="imgProducto.zoomOut()">
                            <v-icon>fas fa-search-minus</v-icon>
                        </v-btn>
                        <v-btn flat icon color="primary" @click="imgProducto.rotate()">
                            <v-icon>fas fa-redo</v-icon>
                        </v-btn>
                        <v-btn flat icon color="primary" @click="imgProducto = null">
                            <v-icon>fas fa-times</v-icon>
                        </v-btn>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-flex>
        <v-flex xs12 sm6>
            <h1>Inputs</h1>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    name: "ProductosForm",

    data() {
        return {
            imgProducto: null
        };
    }
};
</script>

<style>
</style>
