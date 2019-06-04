<template>
    <div>
        <v-layout justify-center wrap>
            <v-flex xs10 v-for="input in formulario.inputs" :key="input.model">
                <v-text-field
                    :class="'input-'"
                    :label="input.label"
                    :hint="input.label"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-btn @click="log()">log</v-btn>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "formulario",

    props: ["formulario"],

    computed: {
        ...mapState("crudx", ["form"])
    },

    methods: {
        log() {
            console.log(this.formulario);
        }
    }
};
</script>

<style>
</style>
