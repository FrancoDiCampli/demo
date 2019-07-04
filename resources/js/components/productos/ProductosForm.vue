<template>
    <div>
        <!-- Componente Formulario -->
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.costo"
                    :rules="[rules.required]"
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
                    :rules="[rules.required]"
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
                    v-model="precio"
                    :rules="[rules.required]"
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
                <v-text-field
                    @keyup="findCategoria()"
                    v-model="form.categoria"
                    :rules="[rules.required]"
                    label="Categoria"
                    box
                    single-line
                ></v-text-field>

                <!-- Tabla Categorias -->
                <transition name="expand">
                    <v-data-table
                        v-show="form.categoria && categorias.length > 0 && !form.categoria_id"
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
                <v-text-field
                    @keyup="findMarca()"
                    v-model="form.marca"
                    :rules="[rules.required]"
                    label="Marca"
                    box
                    single-line
                ></v-text-field>

                <!-- Tabla Marcas -->
                <transition name="expand">
                    <v-data-table
                        v-show="form.marca && marcas.length > 0 && !form.marca_id"
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
                <v-select
                    v-model="form.medida"
                    :items="medidas"
                    :rules="[rules.required]"
                    label="Medida"
                    box
                    single-line
                ></v-select>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="codigo"
                    :rules="[rules.required, rules.cod]"
                    label="Codigo"
                    hint="Codigo"
                    box
                    single-line
                ></v-text-field>
            </v-flex>
            <v-flex xs12 px-3>
                <v-text-field
                    v-model="form.codprov"
                    label="Codigo del Proveedor"
                    hint="Codigo del Proveedor"
                    box
                    single-line
                ></v-text-field>
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
    name: "ProductosForm",

    data() {
        return {
            categorias: [],
            marcas: [],
            categoriaLastId: null,
            medidas: [
                "unidades",
                "miligramos",
                "gramos",
                "kilogramos",
                "toneladas",
                "mililitros",
                "litros",
                "milimetros",
                "centímetros",
                "metros",
                "mm cúbicos",
                "cm cúbicos",
                "metros cuadrados",
                "metros cubicos",
                "gruesa",
                "packs",
                "otras unidades"
            ],
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                cod: value =>
                    (value && value.length == 13) ||
                    "Este campo debe contener si o si 13 digitos"
            }
        };
    },

    computed: {
        ...mapState("crudx", ["form"]),

        codigo: {
            set() {},
            get() {
                if (this.form.categoria) {
                    if (this.form.categoria.length >= 3) {
                        let codigo =
                            this.form.categoria[0] +
                            this.form.categoria[1] +
                            this.form.categoria[2];

                        let newId = this.categoriaLastId + 1;
                        let number = newId.toString();
                        let zeroLength = 10 - number.length;

                        for (let i = 0; i < zeroLength; i++) {
                            codigo += "0";
                        }

                        codigo += number;
                        this.form.codarticulo = codigo.toUpperCase();
                        return codigo.toUpperCase();
                    } else {
                        this.form.codarticulo = null;
                        return null;
                    }
                } else {
                    this.form.codarticulo = null;
                    return null;
                }
            }
        },

        precio: {
            set() {},
            get() {
                if (this.form.costo && this.form.utilidades) {
                    let ganancia =
                        (this.form.utilidades * this.form.costo) / 100;
                    ganancia = ganancia.toFixed(2);

                    this.form.precio =
                        Number(this.form.costo) + Number(ganancia);
                    return Number(this.form.costo) + Number(ganancia);
                } else {
                    this.form.precio = null;
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

            if (this.form.categoria) {
                let response = await this.index({
                    url: "/api/categorias/index",
                    buscarCategoria: this.form.categoria,
                    limit: 5
                });

                this.categorias = response.categorias;
            }
        },

        selectCategoria(categoria) {
            this.categorias = [];
            this.form.categoria = categoria.categoria;
            this.form.categoria_id = categoria.id;
        },

        findMarca: async function() {
            this.form.marca_id = null;
            if (this.form.marca) {
                let response = await this.index({
                    url: "/api/marcas/index",
                    buscarMarca: this.form.marca,
                    limit: 5
                });

                this.marcas = response.marcas;
            }
        },

        selectMarca(marca) {
            this.marcas = [];
            this.form.marca = marca.marca;
            this.form.marca_id = marca.id;
        },

        getLastId: async function() {
            let response = await this.index({
                url: "/api/categorias/index",
                limit: 1
            });

            this.categoriaLastId = response.categorias[0].id;
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
