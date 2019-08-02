<template>
    <div>
        <!-- Componente Formulario -->
        <v-layout justify-space-around wrap>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.costo"
                    :rules="[rules.required]"
                    label="Costo"
                    box
                    type="number"
                ></v-text-field>
                <Error tag="costo"></Error>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="form.utilidades"
                    :rules="[rules.required]"
                    label="Utilidades %"
                    box
                    type="number"
                ></v-text-field>
                <Error tag="utilidades"></Error>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-select
                    v-model="form.alicuota"
                    :items="alicuotas"
                    :rules="[rules.required]"
                    label="Alicuota %"
                    box
                ></v-select>
                <Error tag="alicuota"></Error>
            </v-flex>
            <v-flex xs12 sm6 lg3 px-3>
                <v-text-field
                    v-model="precio"
                    :rules="[rules.required]"
                    label="Precio"
                    box
                    type="number"
                    disabled
                ></v-text-field>
                <Error tag="precio"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-select
                    v-model="form.medida"
                    :items="medidas"
                    :rules="[rules.required]"
                    label="Medida"
                    box
                ></v-select>
                <Error tag="medido"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="form.stockminimo"
                    :rules="[rules.required]"
                    label="Stock Minimo"
                    box
                    type="number"
                ></v-text-field>
                <Error tag="stockminimo"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <!-- Input Categorias -->
                <v-text-field
                    @keyup="findCategoria()"
                    v-model="form.categoria"
                    :rules="[rules.required]"
                    label="Categoria"
                    box
                ></v-text-field>
                <Error tag="categoria_id"></Error>
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
                ></v-text-field>
                <Error tag="marca_id"></Error>
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
                <v-text-field
                    v-model="codigo"
                    disabled
                    :rules="[rules.required, rules.cod]"
                    label="Codigo"
                    box
                ></v-text-field>
                <Error tag="codarticulo"></Error>
            </v-flex>
            <v-flex xs12 sm6 px-3>
                <v-text-field
                    v-model="form.codprov"
                    :disabled="disabledProveedor"
                    label="Codigo del Proveedor"
                    box
                ></v-text-field>
                <Error tag="codprov"></Error>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Components
import Error from "../../crudx/error.vue";

//Vuex
import { mapState, mapActions } from "vuex";

//Axios
import axios from "axios";

export default {
    name: "ProductosForm",

    props: ["mode", "disabledProveedor"],

    data() {
        return {
            categorias: [],
            marcas: [],
            categoriaLastId: null,
            medidas: [
                "Unidades",
                "Miligramos",
                "Gramos",
                "Kilogramos",
                "Toneladas",
                "Mililitros",
                "Litros",
                "Milimetros",
                "Centímetros",
                "Metros",
                "Mm Cúbicos",
                "Cm Cúbicos",
                "Metros Cuadrados",
                "Metros Cubicos",
                "Gruesa",
                "Packs",
                "Otras Unidades"
            ],
            alicuotas: [21, 10.5],
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                cod: value =>
                    (value && value.length == 13) ||
                    "Este campo debe contener si o si 13 digitos"
            }
        };
    },

    components: {
        Error
    },

    computed: {
        ...mapState("crudx", ["form"]),

        codigo: {
            set() {},
            get() {
                if (this.mode == "create") {
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
                } else {
                    return this.form.codarticulo;
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
                    url: "/api/categorias",
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
                    url: "/api/marcas",
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
                url: "/api/articulos",
                limit: 1
            });
            if (response.articulos.length > 0) {
                this.categoriaLastId = response.articulos[0].id;
            } else {
                this.categoriaLastId = 0;
            }
        }
    }
};
</script>

<style>
</style>
