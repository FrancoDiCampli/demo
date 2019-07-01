<template>
    <div>
        <!-- Componente Formulario -->
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 px-2>
                <v-layout justify-center wrap>
                    <croppa
                        v-model="form.foto"
                        :width="250"
                        :height="250"
                        placeholder="Foto"
                        placeholder-color="#000"
                        :placeholder-font-size="24"
                        canvas-color="transparent"
                        :show-remove-button="true"
                        remove-button-color="#26A69A"
                        :show-loading="true"
                        :loading-size="25"
                        :prevent-white-space="true"
                        :zoom-speed="10"
                    ></croppa>
                    <v-flex xs12 px-2>
                        <v-layout justify-center>
                            <v-btn flat icon color="primary" @click="form.foto.zoomIn()">
                                <v-icon>fas fa-plus</v-icon>
                            </v-btn>
                            <v-btn flat icon color="primary" @click="form.foto.zoomOut()">
                                <v-icon>fas fa-minus</v-icon>
                            </v-btn>
                            <v-btn flat icon color="primary" @click="form.foto.rotate()">
                                <v-icon>fas fa-redo-alt</v-icon>
                            </v-btn>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs12 sm6 px-2>
                <v-flex xs12 px-2>
                    <v-text-field
                        v-model="form.articulo"
                        label="Articulo"
                        hint="Articulo"
                        box
                        single-line
                    ></v-text-field>
                </v-flex>
                <v-flex xs12 px-2>
                    <v-textarea
                        v-model="form.descripcion"
                        label="Descripción"
                        hint="Descripción"
                        box
                        single-line
                        height="165"
                        no-resize
                    ></v-textarea>
                </v-flex>
            </v-flex>
        </v-layout>
        <br />
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.costo"
                    label="Costo"
                    hint="Costo"
                    box
                    single-line
                    type="number"
                    class="input-number"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.utilidades"
                    label="Utilidades"
                    hint="Utilidades"
                    box
                    single-line
                    type="number"
                    class="input-number"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.alicuota"
                    label="Alicuota"
                    hint="Alicuota"
                    box
                    single-line
                    type="number"
                    class="input-number"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.precio"
                    label="Precio"
                    hint="Precio"
                    box
                    single-line
                    type="number"
                    class="input-number"
                    disabled
                ></v-text-field>
            </v-flex>

            <v-flex xs12 sm6 px-3>
                <!-- Input Categorias -->
                <v-form ref="formFindCategoria">
                    <v-text-field
                        @keyup="findCategoria()"
                        v-model="categoria"
                        :rules="[rules.required]"
                        label="Categoria"
                        box
                        single-line
                    ></v-text-field>
                </v-form>

                <!-- Tabla Categorias -->
                <transition name="expand">
                    <v-data-table
                        v-show="categoria != null && categoria != '' && categorias.length > 0 && !form.categoria_id"
                        no-data-text="La categoria no se encuentra en la base de datos."
                        hide-actions
                        hide-headers
                        :items="categorias"
                        class="search-table"
                    >
                        <template v-slot:items="categoria">
                            <tr @click="selectCategoria(categoria.item)" style="cursor: pointer;">
                                <td>{{ categoria.item.categoria }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <!-- Input Marcas -->
                <v-form ref="formFindMarca">
                    <v-text-field
                        @keyup="findMarca()"
                        v-model="marca"
                        :rules="[rules.required]"
                        label="Marca"
                        box
                        single-line
                    ></v-text-field>
                </v-form>

                <!-- Tabla Marcas -->
                <transition name="expand">
                    <v-data-table
                        v-show="marca != null && marca != '' && marcas.length > 0 && !form.marca_id"
                        no-data-text="La marca no se encuentra en la base de datos."
                        hide-actions
                        hide-headers
                        :items="marcas"
                        class="search-table"
                    >
                        <template v-slot:items="marca">
                            <tr @click="selectMarca(marca.item)" style="cursor: pointer;">
                                <td>{{ marca.item.marca }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </transition>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field v-model="form.medida" label="Medida" box single-line></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field v-model="codigo" label="Codigo" hint="Codigo" box single-line></v-text-field>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
//Vuex
import { mapState, mapActions } from "vuex";

//Axios
import axios from "axios";

export default {
    name: "ProductosForm.vue",

    data() {
        return {
            categoria: null,
            categorias: [],
            marca: null,
            marcas: [],
            marcaLastId: null,
            rules: {
                required: value => !!value || "Este campo es obligatorio"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"]),

        codigo: {
            set() {},
            get() {
                if (this.marca != null && this.marca != "") {
                    if (this.marca.length >= 3) {
                        let codigo =
                            this.marca[0] + this.marca[1] + this.marca[2];

                        let number = this.newId.toString();
                        let zeroLength = 10 - number.length;

                        for (let i = 0; i < zeroLength; i++) {
                            codigo += "0";
                        }

                        codigo += number;

                        return codigo.toUpperCase();
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }
        }
    },

    mounted() {
        this.getLastId();
    },

    methods: {
        ...mapActions("crudx", ["index"]),

        findCategoria: async function() {
            this.form.categoria_id = null;
            if (this.$refs.formFindCategoria.validate()) {
                let response = await this.index({
                    url: "/api/categorias",
                    buscarCategoria: this.categoria,
                    limit: 5
                });

                this.categorias = response.categorias;
            }
        },

        selectCategoria(categoria) {
            this.categorias = [];
            this.categoria = categoria.categoria;
            this.form.categoria_id = categoria.id;
        },

        findMarca: async function() {
            this.form.marca_id = null;
            if (this.$refs.formFindMarca.validate()) {
                let response = await this.index({
                    url: "/api/marcas",
                    buscarMarca: this.marca,
                    limit: 5
                });

                this.marcas = response.marcas;
            }
        },

        selectMarca(marca) {
            this.marcas = [];
            this.marca = marca.marca;
            this.form.marca_id = marca.id;
        },

        getLastId: async function() {
            let response = await this.index({
                url: "/api/marcas",
                limit: 1
            });

            this.marcaLastId = response.marcas[0].id;
        }
    }
};
</script>

<style>
.input-number input[type="number"] {
    -moz-appearance: textfield;
}
.input-number input::-webkit-outer-spin-button,
.input-number input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

.search-table {
    border: solid 2px #26a69a;
    margin-top: -30px;
    border-top: none;
    margin-bottom: 20px;
    border-radius: 0px 0px 5px 5px;
}

.expansion-border {
    border-bottom: 1px solid #aaaaaa;
}

.expand-transition {
    transition: all 0.5s ease;
}

.expand-enter,
.expand-leave {
    height: 0;
    opacity: 0;
}
</style>
