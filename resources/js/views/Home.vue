<template>
    <div>
        <br />
        <br />
        <v-layout wrap>
            <v-flex xs12>
                <v-layout justify-center>
                    <img src="images/logo.png" width="300" height="300" />
                </v-layout>
            </v-flex>
            <v-flex xs12>
                <br />
                <h1 class="text-xs-center display-2">{{ comerce.nombre }}</h1>
                <h1 class="text-xs-center font-weight-light">{{ comerce.lema }}</h1>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Home",

    data() {
        return {
            comerce: []
        };
    },

    mounted() {
        this.getComercialConfig();
    },

    methods: {
        getComercialConfig() {
            axios
                .get("/api/config/comercial")
                .then(response => {
                    this.comerce = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
