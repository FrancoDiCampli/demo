<template>
    <div>
        <v-layout row>
            <v-flex xs12>
                <v-card>
                    <div v-if="mode == 'edit'">
                        <v-card-title>
                            <v-layout justify-space-between>
                                <h2>Editar Producto</h2>
                            </v-layout>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form ref="productosEditForm" @submit.prevent="updateProducto()">
                                <v-layout justify-space-around wrap>
                                    <v-flex xs12 sm6 px-2>
                                        <v-layout justify-center wrap>
                                            <croppa
                                                v-model="foto"
                                                :initial-image="showData.articulo.foto"
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
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="foto.zoomIn()"
                                                    >
                                                        <v-icon>fas fa-search-plus</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="foto.zoomOut()"
                                                    >
                                                        <v-icon>fas fa-search-minus</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        color="primary"
                                                        @click="foto.rotate()"
                                                    >
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
                                <ProductosForm mode="edit"></ProductosForm>
                                <v-layout justify-center>
                                    <v-btn
                                        :disabled="inProcess"
                                        @click="mode = 'show'"
                                        outline
                                        color="primary"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :loading="inProcess"
                                        :disabled="inProcess"
                                        type="submit"
                                        color="primary"
                                    >Editar</v-btn>
                                </v-layout>
                            </v-form>
                        </v-card-text>
                    </div>
                    <div v-else>
                        <v-img
                            class="hidden-sm-and-up"
                            :src="showData.articulo.foto"
                            height="250px"
                        >
                            <v-layout column fill-height>
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <div v-if="rol == 'superAdmin' || rol == 'admin'">
                                        <v-menu style="margin: 15px 0px;">
                                            <template v-slot:activator="{ on }">
                                                <v-btn
                                                    absolute
                                                    right
                                                    flat
                                                    icon
                                                    dark
                                                    color="white"
                                                    v-on="on"
                                                >
                                                    <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-tile @click="editProducto()">
                                                    <v-list-tile-title>Editar</v-list-tile-title>
                                                </v-list-tile>
                                                <v-list-tile @click="mode = 'delete'">
                                                    <v-list-tile-title>Eliminar</v-list-tile-title>
                                                </v-list-tile>
                                            </v-list>
                                        </v-menu>
                                    </div>
                                </v-card-title>

                                <v-spacer></v-spacer>

                                <v-card-title>
                                    <v-layout justify-center wrap>
                                        <v-flex xs12>
                                            <h1
                                                class="text-xs-center white--text"
                                            >{{ showData.articulo.articulo }}</h1>
                                        </v-flex>
                                        <v-flex xs12>
                                            <h3
                                                class="text-xs-center white--text"
                                            >{{ showData.articulo.descripcion }}</h3>
                                        </v-flex>
                                    </v-layout>
                                </v-card-title>
                            </v-layout>
                        </v-img>
                        <div class="hidden-xs-only">
                            <br />
                            <div v-if="rol == 'superAdmin' || rol == 'admin'">
                                <v-menu>
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            absolute
                                            right
                                            flat
                                            icon
                                            dark
                                            color="primary"
                                            v-on="on"
                                        >
                                            <v-icon size="medium">fas fa-ellipsis-v</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-tile @click="editProducto()">
                                            <v-list-tile-title>Editar</v-list-tile-title>
                                        </v-list-tile>
                                        <v-list-tile @click="mode = 'delete'">
                                            <v-list-tile-title>Eliminar</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </div>
                            <v-flex xs12>
                                <v-layout justify-center>
                                    <v-avatar size="200">
                                        <img :src="showData.articulo.foto" />
                                    </v-avatar>
                                </v-layout>
                            </v-flex>

                            <v-flex xs12>
                                <br />
                                <h1
                                    class="text-xs-center primary--text"
                                >{{ showData.articulo.articulo }}</h1>
                            </v-flex>
                            <v-flex xs12>
                                <h3
                                    class="text-xs-center primary--text"
                                >{{ showData.articulo.descripcion }}</h3>
                            </v-flex>
                        </div>
                        <br />
                        <div v-if="mode == 'show'">
                            <template>
                                <div>
                                    <v-tabs
                                        fixed-tabs
                                        grow
                                        slider-color="primary"
                                        active-class="primary--text"
                                    >
                                        <v-tab>Datos</v-tab>
                                        <v-tab>Inventarios</v-tab>
                                        <v-tab-item>
                                            <ProductosShowData></ProductosShowData>
                                        </v-tab-item>
                                        <v-tab-item>
                                            <ProductosShowInventario></ProductosShowInventario>
                                        </v-tab-item>
                                    </v-tabs>
                                </div>
                            </template>
                        </div>
                        <div v-else-if="mode == 'delete'">
                            <v-alert :value="true" color="error">
                                <h2 class="text-xs-center">¿Estas Seguro?</h2>
                                <br />
                                <v-divider dark></v-divider>
                                <br />
                                <p class="text-xs-center">¿Realmente deseas eliminar este Producto?</p>
                                <p class="text-xs-center">Este Cambio es Irreversible</p>
                                <br />
                                <v-layout justify-center>
                                    <v-btn
                                        @click="mode = 'show'"
                                        :disabled="inProcess"
                                        class="elevation-0 red--text"
                                        color="white"
                                    >Cancelar</v-btn>
                                    <v-btn
                                        :loading="inProcess"
                                        :disabled="inProcess"
                                        @click="deleteProducto()"
                                        outline
                                        color="white"
                                    >Eliminar</v-btn>
                                </v-layout>
                            </v-alert>
                        </div>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
// Components
import ProductosShowData from "./ProductosShowData.vue";
import ProductosShowInventario from "./ProductosShowInventario.vue";
import ProductosForm from "./ProductosForm.vue";
import Error from "../../crudx/error.vue";

// Vuex
import { mapState, mapActions } from "vuex";

export default {
    name: "ProductosShow",

    data() {
        return {
            mode: "show",
            foto: null,
            rules: {
                required: value => !!value || "Este campo es obligatorio",
                max: value =>
                    (value && value.length <= 190) ||
                    "Este campo no puede contener mas de 190 digitos"
            }
        };
    },

    components: {
        ProductosShowData,
        ProductosShowInventario,
        ProductosForm,
        Error
    },

    computed: {
        ...mapState("crudx", ["inProcess", "form", "showData"]),
        ...mapState("auth", ["rol"])
    },

    methods: {
        ...mapActions("crudx", ["index", "show", "edit", "update", "destroy"]),

        editProducto: async function() {
            await this.edit({ data: this.showData.articulo });

            if (this.form.alicuota == "21.00") {
                this.form.alicuota = 21;
            } else {
                this.form.alicuota = 10.5;
            }

            this.form.categoria = this.showData.articulo.categoria.categoria;
            this.form.marca = this.showData.articulo.marca.marca;

            this.mode = "edit";
        },

        updateProducto: async function() {
            if (this.$refs.productosEditForm.validate()) {
                let id = this.form.id;
                this.form.foto = this.foto.generateDataUrl();
                await this.update({ url: "/api/articulos/" + id });
                await this.show({ url: "/api/articulos/" + id });
                this.mode = "show";
                this.$refs.productosEditForm.reset();
                this.index({ url: "/api/articulos" });
            }
        },

        deleteProducto: async function() {
            await this.destroy({
                url: "/api/articulos/" + this.showData.articulo.id
            });
            await this.index({ url: "/api/articulos" });
            this.mode = "show";
            this.$router.push("/productos");
        }
    }
};
</script>

<style>
</style>
