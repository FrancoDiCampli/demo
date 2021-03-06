<template>
    <div>
        <v-tooltip left>
            <template v-slot:activator="{ on }">
                <v-btn dark fab fixed right bottom @click="goBack()" color="primary" v-on="on">
                    <v-icon>fas fa-chevron-left</v-icon>
                </v-btn>
            </template>
            <span>Volver</span>
        </v-tooltip>
        <template>
            <!-- Barra de progreso circular -->
            <div class="loading" v-show="process">
                <v-layout justify-center>
                    <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
                </v-layout>
            </div>
        </template>
        <v-card v-show="!process">
            <v-card-text>
                <v-layout justify-space-between>
                    <h2>Nuevo Producto</h2>
                </v-layout>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-text>
                <!-- Formulario formulario para agregar un cliente -->
                <v-form ref="productosForm" @submit.prevent="preventSave">
                    <v-layout justify-space-around wrap>
                        <v-flex xs12 sm6 px-2>
                            <v-layout justify-center wrap>
                                <croppa
                                    v-model="foto"
                                    :width="250"
                                    :height="250"
                                    placeholder="Foto"
                                    placeholder-color="#000"
                                    :placeholder-font-size="24"
                                    canvas-color="transparent"
                                    :show-remove-button="false"
                                    :show-loading="true"
                                    :loading-size="25"
                                    :prevent-white-space="true"
                                    :zoom-speed="10"
                                ></croppa>
                                <v-flex xs12 px-2>
                                    <v-layout justify-center>
                                        <v-btn flat icon color="primary" @click="foto.zoomIn()">
                                            <v-icon>fas fa-search-plus</v-icon>
                                        </v-btn>
                                        <v-btn flat icon color="primary" @click="foto.zoomOut()">
                                            <v-icon>fas fa-search-minus</v-icon>
                                        </v-btn>
                                        <v-btn flat icon color="primary" @click="foto.rotate()">
                                            <v-icon>fas fa-redo-alt</v-icon>
                                        </v-btn>
                                        <div v-if="foto != null">
                                            <v-btn
                                                v-show="foto.hasImage()"
                                                flat
                                                icon
                                                color="primary"
                                                @click="foto.remove()"
                                            >
                                                <v-icon>fas fa-times</v-icon>
                                            </v-btn>
                                            <v-btn
                                                v-show="!foto.hasImage()"
                                                flat
                                                icon
                                                color="primary"
                                                @click="foto.chooseFile()"
                                            >
                                                <v-icon>fas fa-plus</v-icon>
                                            </v-btn>
                                        </div>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12 sm6 px-2>
                            <v-flex xs12 px-2>
                                <v-text-field
                                    v-model="form.articulo"
                                    :rules="[rules.required]"
                                    label="Articulo"
                                    hint="Articulo"
                                    box
                                    single-line
                                ></v-text-field>
                                <Error tag="articulo"></Error>
                            </v-flex>
                            <v-flex xs12 px-2>
                                <v-textarea
                                    v-model="form.descripcion"
                                    :rules="[rules.required,rules.max]"
                                    label="Descripción"
                                    hint="Descripción"
                                    box
                                    single-line
                                    height="165"
                                    no-resize
                                ></v-textarea>
                                <Error tag="descripcion"></Error>
                            </v-flex>
                        </v-flex>
                    </v-layout>
                    <br />
                    <ProductosForm mode="create" :disabledProveedor="false"></ProductosForm>
                    <v-layout justify-center>
                        <v-btn :disabled="process" type="submit" color="primary">Guardar</v-btn>
                    </v-layout>
                </v-form>
            </v-card-text>
        </v-card>

        <v-dialog v-model="saveCategoriaMarcaDialog" width="750" persistent>
            <v-card>
                <!-- Modal Header -->
                <v-card-text>
                    <v-layout justify-space-between>
                        <h2>¡ADVERTENCIA!</h2>
                    </v-layout>
                </v-card-text>
                <v-divider></v-divider>
                <!-- Modal body -->
                <v-card-text>
                    <v-layout justify-center wrap>
                        <v-flex xs12>
                            <h3 class="text-xs-center font-weight-regular">{{ msgCategoria }}</h3>
                        </v-flex>

                        <v-flex xs12>
                            <h3 class="text-xs-center font-weight-regular">{{ msgMarca }}</h3>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-text>
                    <v-layout justify-end>
                        <v-btn
                            :disabled="process"
                            @click="saveCategoriaMarcaDialog = false"
                            outline
                            color="primary"
                        >CANCELAR</v-btn>
                        <v-btn :disabled="process" @click="confirmSave()" color="primary">ACEPTAR</v-btn>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
// Components
import ProductosForm from "../../components/productos/ProductosForm.vue";
import Error from "../../crudx/error.vue";

// Axios
import axios from "axios";

// Vuex
import { mapActions, mapState, mapMutations } from "vuex";

export default {
    name: "ProductoNew",

    data() {
        return {
            foto: null,
            saveCategoriaMarcaDialog: false,
            msgCategoria: "",
            msgMarca: "",
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 190) ||
                    "Este campo no puede contener mas de 190 digitos"
            },
            process: false
        };
    },

    components: {
        ProductosForm,
        Error
    },

    computed: {
        ...mapState("crudx", ["form"])
    },

    methods: {
        ...mapMutations("crudx", ["resetForm"]),
        ...mapActions("crudx", ["save"]),

        preventSave: async function() {
            if (this.$refs.productosForm.validate()) {
                if (this.form.categoria_id && this.form.marca_id) {
                    this.process = true;
                    await this.saveProducto();
                    this.process = false;
                    this.$router.push("/productos");
                } else {
                    if (!this.form.categoria_id) {
                        this.msgCategoria =
                            "Se añadirá la siguiente categoría a la base de datos: “" +
                            this.form.categoria +
                            "”.";
                    }

                    if (!this.form.marca_id) {
                        this.msgMarca =
                            "Se añadirá la siguiente marca a la base de datos: “" +
                            this.form.marca +
                            "”.";
                    }

                    this.saveCategoriaMarcaDialog = true;
                }
            }
        },

        confirmSave: async function() {
            this.process = true;
            this.saveCategoriaMarcaDialog = false;
            if (!this.form.categoria_id) {
                this.form.categoria_id = await this.saveCategoria();
            }

            if (!this.form.marca_id) {
                this.form.marca_id = await this.saveMarca();
            }

            await this.saveProducto();
            this.process = false;
            this.$router.push("/productos");
        },

        saveCategoria() {
            return new Promise(resolve => {
                axios
                    .post("/api/categorias", {
                        categoria: this.form.categoria
                    })
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        throw new Error(error);
                    });
            });
        },

        saveMarca() {
            return new Promise(resolve => {
                axios
                    .post("/api/marcas", {
                        marca: this.form.marca
                    })
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        throw new Error(error);
                    });
            });
        },

        saveProducto: async function() {
            this.form.foto = this.foto.generateDataUrl();
            await this.save({ url: "/api/articulos" });
        },

        goBack() {
            this.resetForm();
            this.$router.push("/productos");
        }
    }
};
</script>

<style>
</style>
