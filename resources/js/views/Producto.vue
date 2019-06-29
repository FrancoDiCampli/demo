<template>
  <div>
    <!-- Boton para agregar un nuevo Producto -->
    <v-btn
      dark
      fab
      fixed
      right
      bottom
      color="primary"
      @click="createProductosDialog = !createProductosDialog"
    >
      <v-icon>fas fa-plus</v-icon>
    </v-btn>

    <!-- create productos dialog -->
    <!-- Modal nuevo cliente -->
    <v-dialog v-model="createProductosDialog" width="750" persistent>
      <v-card>
        <!-- Modal Header -->
        <v-card-text>
          <v-layout justify-space-between>
            <h2>Nuevo CLiente</h2>
            <!-- Boton cerrar modal -->
            <v-btn
              @click="createProductosDialog = false; $refs.productosForm.reset();"
              flat
              icon
              style="margin: 0; padding: 0;"
            >
              <v-icon>fas fa-times</v-icon>
            </v-btn>
          </v-layout>
        </v-card-text>
        <v-divider></v-divider>
        <!-- Modal body -->
        <v-card-text>
          <template>
            <!-- Barra de progreso circular -->
            <div class="loading" v-show="inProcess">
              <v-layout justify-center>
                <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
              </v-layout>
            </div>
          </template>
          <!-- Formulario formulario para agregar un cliente -->
          <v-form ref="productosForm" @submit.prevent="saveProducto">
            <!-- Componente Formulario -->
            <v-layout justify-space-around wrap>
              <v-flex px-2 xs12>
                <croppa
                  v-model="myCroppa"
                  :width="250"
                  :height="250"
                  placeholder="Foto de Perfil"
                  placeholder-color="#000"
                  :placeholder-font-size="24"
                  canvas-color="transparent"
                  :show-remove-button="true"
                  remove-button-color="red"
                  :show-loading="true"
                  :loading-size="25"
                  :prevent-white-space="true"
                  :zoom-speed="10"
                ></croppa>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.codarticulo" label="Codigo"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.articulo" label="Articulo"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.descripcion" label="Descripción"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.medida" label="Medida"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.costo" label="Costo"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.utilidades" label="Utilidades"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.precio" label="Precio"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.alicuota" label="alicutoa"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.marca_id" label="Marca"></v-text-field>
              </v-flex>
              <v-flex px-2 xs12 sm6>
                <v-text-field v-model="form.categoria_id" label="Categoria"></v-text-field>
              </v-flex>
            </v-layout>
            <v-layout justify-center>
              <v-btn :disabled="inProcess" type="submit" color="primary">Guardar</v-btn>
            </v-layout>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Index Productos -->
    <ProductosIndex></ProductosIndex>
  </div>
</template>

<script>
// Components
import ProductosIndex from "../components/productos/ProductosIndex.vue";
import { mapActions, mapState } from "vuex";

export default {
  name: "Producto",

  data() {
    return {
      createProductosDialog: false,
      myCroppa: null
    };
  },

  components: {
    ProductosIndex
  },

  computed: {
    ...mapState("crudx", ["form", "inProcess"])
  },

  methods: {
    ...mapActions("crudx", ["save"]),
    saveProducto() {
      this.form.foto = this.myCroppa.generateDataUrl();
      this.save({ url: "/api/articulos" });
    }
  }
};
</script>

<style>
</style>

