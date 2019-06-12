<template>
    <div>
        <v-layout wrap justify-space-around>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="form.articulo"
                    label="Articulo"
                    hint="Articulo"
                    :rules="[rules.required]"
                    box
                    single-line
                    color="primary"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-text-field
                    v-model="form.descripcion"
                    label="Descripcion"
                    hint="Descripcion"
                    :rules="[rules.required]"
                    box
                    single-line
                    color="primary"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
                <v-select
                    v-model="form.categoria_id"
                    :items="categorias"
                    item-text="categoria"
                    item-value="id"
                    :rules="[rules.required]"
                    label="Categoria"
                    hint="Categoria"
                    box
                    single-line
                    color="primary"
                ></v-select>
            </v-flex>
            <v-flex xs12 sm5>
                <v-select
                    v-model="form.marca_id"
                    :items="marcas"
                    item-text="marca"
                    item-value="id"
                    :rules="[rules.required]"
                    label="Marcas"
                    hint="Marcas"
                    box
                    single-line
                    color="primary"
                ></v-select>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Vuex
import { mapState } from "vuex";

//Axios
import axios from "axios";

export default {
    name: "ProductosForm.vue",

    data() {
        return {
            categorias: [],
            marcas: [],
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"])
    },

    mounted() {
        this.getCategorias();
        this.getMarcas();
    },

    methods: {
        getCategorias() {
            axios
                .get("api/categorias")
                .then(response => {
                    this.categorias = response.data;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },

        getMarcas() {
            axios
                .get("api/marcas")
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        }
    }
};
</script>

<style>
</style>
