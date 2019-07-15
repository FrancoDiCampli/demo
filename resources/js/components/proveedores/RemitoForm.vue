<template>
  <div>
    <v-form ref="formFactura" @submit.prevent="saveFactura">
      <!-- Facturas Headers -->
      <div>
        <v-card-title>
          <v-layout justify-space-around wrap>
            <v-flex xs12 sm5 mx-1>
              <h1 class="text-xs-center text-sm-left">Nuevo Remito</h1>
            </v-flex>
            <v-flex xs12 sm5 mx-1 class="data text-xs-center text-sm-right">
              <p>
                <b>Punto de Venta:</b> 0003
              </p>
              <p>
                <b>Comprobante Nº:</b> 2
              </p>
            </v-flex>
          </v-layout>
        </v-card-title>
        <v-divider></v-divider>
        <br />

        <!-- Snackbar -->
        <v-snackbar color="primary" v-model="snackbar" :timeout="6000" right top>
          {{ snackbarText }} GUARDADO
          <v-btn color="white" flat @click="snackbar = false" icon>
            <v-icon>fas fa-times</v-icon>
          </v-btn>
        </v-snackbar>
      </div>
      <!-- End Snack -->

      <!-- Proveedores -->
      <div>
        <!-- Formulario -->
        <v-layout justify-space-around wrap>
          <v-flex xs11 sm11>
            <!-- Input Proveedor -->
            <v-form ref="formFindSupplier">
              <v-text-field
                @keyup="findSupplier()"
                v-model="form.supplier"
                label="Proveedor"
                box
                single-line
              ></v-text-field>
            </v-form>

            <!-- Tabla Proveedores -->
            <transition name="expand">
              <v-data-table
                v-show="form.supplier && suppliers.length > 0"
                no-data-text="El Proveedores no se encuentra en la base de datos."
                hide-actions
                hide-headers
                :items="suppliers"
                class="search-table"
              >
                <template v-slot:items="supplier">
                  <tr @click="selectSupplier(supplier.item)" style="cursor: pointer;">
                    <td>{{ supplier.item.cuit }}</td>
                    <td>{{ supplier.item.razonsocial }}</td>
                  </tr>
                </template>
              </v-data-table>
            </transition>
          </v-flex>
        </v-layout>
        <!-- Detalles Proveedor -->
        <v-layout v-if="detailSupplier.supplier" justify-space-around>
          <v-flex xs11>
            <template>
              <v-expansion-panel class="elevation-0 expansion-border">
                <v-expansion-panel-content>
                  <template v-slot:header>
                    <div>Más Detalles</div>
                  </template>
                  <v-card-text>
                    <p>
                      <b>CUIT:</b>
                      {{detailSupplier.supplier.cuit}}
                    </p>
                    <p>
                      <b>Razón Social:</b>
                      {{detailSupplier.supplier.razonsocial}}
                    </p>
                    <p>
                      <b>Domicilio:</b>
                      {{detailSupplier.supplier.direccion}}
                    </p>
                  </v-card-text>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </template>
            <br />
          </v-flex>
        </v-layout>
      </div>
      <!-- EndProveedores -->

      <div>
        <!-- Formulario -->
        <v-form ref="formDetalles">
          <v-layout justify-space-around wrap>
            <v-flex xs11>
              <!-- Input Buscar Articulos -->
              <v-form ref="formFindArticle">
                <v-text-field
                  @keyup="findArticle()"
                  autofocus
                  v-model="article"
                  :rules="[rules.required]"
                  label="Articulo"
                  box
                  single-line
                ></v-text-field>
              </v-form>

              <!-- Tabla Buscar Articulos -->
              <transition>
                <v-data-table
                  v-show="article != null && article != '' && products.length > 0"
                  no-data-text="El producto no se encuentra en la base de datos."
                  hide-actions
                  hide-headers
                  :items="products"
                  class="search-table"
                >
                  <template v-slot:items="article">
                    <tr @click="selectArticle(article.item)" style="cursor: pointer;">
                      <td>{{ article.item.codarticulo }}</td>
                      <td>{{ article.item.articulo }}</td>
                      <td>{{ article.item.precio }}</td>
                    </tr>
                  </template>
                </v-data-table>
              </transition>
            </v-flex>

            <!-- Input Cantidad -->
            <v-flex xs11 sm2 mx-1>
              <v-text-field
                v-model="quantity"
                :disabled="article == null || article == '' ? true : false"
                @keyup.enter="fillDetails()"
                label="Cantidad"
                hint="Cantidad"
                box
                single-line
              ></v-text-field>
            </v-flex>

            <!-- Lote -->
            <v-flex xs11 sm2 mx-1>
              <v-text-field
                v-model="lote"
                :disabled="article == null || article == '' ? true : false"
                @keyup.enter="fillDetails()"
                label="Lote"
                hint="Lote"
                box
                single-line
              ></v-text-field>
            </v-flex>

            <!-- Input Precio -->
            <v-flex xs11 sm2 mx-1>
              <v-text-field
                v-model="price"
                :disabled="article == null || article == '' ? true : false"
                @keyup.enter="fillDetails()"
                label="Precio"
                hint="Precio"
                box
                single-line
              ></v-text-field>
            </v-flex>

            <!-- Input Subtotal -->
            <v-flex xs11 sm2 mx-1>
              <v-text-field
                v-model="subtotal"
                :rules="[rules.required]"
                label="Subtotal"
                disabled
                box
                single-line
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-form>

        <!-- Tabla Detalles -->
        <v-layout justify-space-around>
          <v-flex xs11>
            <v-data-table :headers="detailsHeader" :items="details" hide-actions>
              <template v-slot:items="detail">
                <td>{{ detail.item.articulo }}</td>
                <td>
                  <v-edit-dialog :return-value.sync="detail.item.cantidad" lazy>
                    {{ detail.item.cantidad }}
                    <template v-slot:input>
                      <v-text-field
                        v-model="detail.item.cantidad"
                        label="Cantidad"
                        single-line
                        @keyup="updateDetails()"
                      ></v-text-field>
                    </template>
                  </v-edit-dialog>
                </td>
                <td>
                  <v-edit-dialog :return-value.sync="detail.item.lote" lazy>
                    {{ detail.item.lote }}
                    <template v-slot:input>
                      <v-text-field
                        v-model="detail.item.lote"
                        label="Lote"
                        single-line
                        @keyup="updateDetails()"
                      ></v-text-field>
                    </template>
                  </v-edit-dialog>
                </td>
                <td>
                  <v-edit-dialog :return-value.sync="detail.item.precio" lazy>
                    {{ detail.item.precio }}
                    <template v-slot:input>
                      <v-text-field
                        v-model="detail.item.precio"
                        label="Precio"
                        single-line
                        @keyup="updateDetails()"
                      ></v-text-field>
                    </template>
                  </v-edit-dialog>
                </td>
                <td>{{ detail.item.subtotal }}</td>
                <td>
                  <v-btn @click="removeDetail(detail.item)" flat icon color="primary">
                    <v-icon size="medium">fas fa-times</v-icon>
                  </v-btn>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </div>

      <!-- Facturas Resumen -->
      <br />
      <div>
        <v-layout justify-space-around wrap>
          <v-flex xs12 sm5 mx-1>
            <v-layout justify-space-around wrap>
              <v-flex xs11>
                <v-text-field
                  v-model="form.bonificacion"
                  label="Bonificacion"
                  hint="Bonificacion"
                  box
                  single-line
                ></v-text-field>
              </v-flex>
              <v-flex xs11>
                <v-text-field v-model="form.recargo" label="Recargo" hint="Recargo" box single-line></v-text-field>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 sm5 mx-1>
            <v-layout justify-space-around wrap>
              <v-flex xs11>
                <v-text-field
                  v-model="subtotalFactura"
                  disabled
                  :rules="[rules.required]"
                  label="Subtotal"
                  hint="Subtotal"
                  box
                  single-line
                ></v-text-field>
              </v-flex>
              <v-flex xs11>
                <v-text-field
                  v-model="total"
                  disabled
                  :rules="[rules.required]"
                  label="Total"
                  hint="Total"
                  box
                  single-line
                ></v-text-field>
              </v-flex>
              <v-flex xs11></v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
        <v-layout justify-center>
          <v-btn @click="cancelFactura()" outline color="primary">Cancelar</v-btn>
          <v-btn type="submit" color="primary">Guardar</v-btn>
        </v-layout>
        <br />
      </div>
      <!---------------------->
    </v-form>
  </div>
</template>

<script>
import axios from "axios";

//Vuex
import { mapState, mapMutations, mapActions } from "vuex";

export default {
  data() {
    return {
      //Data Proveedor
      suppliers: "",
      detailSupplier: [],
      customers: [],

      //Data Articulos
      article: null,
      article_id: null,
      quantity: null,
      lote: null,
      price: null,
      stock: 0,
      products: [],
      details: [],
      detailsHeader: [
        { text: "Articulo", sortable: false },
        { text: "Cantidad", sortable: false },
        { text: "Lote", sortable: false },
        { text: "Precio", sortable: false, class: "hidden-xs-only" },
        { text: "Subtotal", sortable: false },
        { text: "", sortable: false }
      ],

      //Data General
      snackbar: false,
      snackbarText: "",
      rules: {
        required: value => !!value || "Este campo es obligatorio"
      }
    };
  },
  computed: {
    ...mapState("crudx", ["form"]),

    //Computed Articulos
    subtotal: {
      set() {},

      get() {
        if (
          this.quantity != null &&
          this.quantity != "" &&
          this.price != null &&
          this.price != ""
        ) {
          return this.price * this.quantity;
        } else {
          return null;
        }
      }
    },

    //Computed Resumen
    subtotalFactura: {
      set() {},

      get() {
        if (this.details.length > 0) {
          let sub = 0;
          for (let i = 0; i < this.details.length; i++) {
            sub += this.details[i].subtotal * 1;
          }
          this.form.subtotal = sub;
          return sub;
        } else {
          this.form.subtotal = null;
          return null;
        }
      }
    },

    total: {
      set() {},
      get() {
        if (this.subtotalFactura != null) {
          let boni = 0;
          let reca = 0;

          if (this.form.bonificacion) {
            if (this.form.bonificacion.length > 0) {
              boni = (this.form.bonificacion * this.subtotalFactura) / 100;
            }
          }

          if (this.form.recargo) {
            if (this.form.recargo.length > 0) {
              reca = (this.form.recargo * this.subtotalFactura) / 100;
            }
          }
          this.form.total = this.subtotalFactura - boni + reca;
          return this.subtotalFactura - boni + reca;
        } else {
          this.form.total = null;
          return null;
        }
      }
    }
  },

  mounted() {
    //Mounted Clientes
  },

  methods: {
    ...mapActions("crudx", ["index", "save"]),

    //Metodos Proveedores

    // Buscar los Proveedores
    findSupplier: async function() {
      this.detailSupplier = [];

      if (this.form.supplier) {
        let response = await this.index({
          url: "/api/suppliers",
          buscarProveedor: this.form.supplier
        });
        this.suppliers = response.suppliers;
      }
    },

    // Seleccionar un Proveedor
    selectSupplier(supplier) {
      this.suppliers = [];
      this.detailSupplier = [];
      this.form.supplier = supplier.razonsocial;
      this.form.supplier_id = supplier.id;

      axios
        .get("/api/suppliers/" + supplier.id)
        .then(response => {
          this.detailSupplier = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    //Metodos Articulos

    //Buscar Articulo
    findArticle: async function() {
      this.$refs.formDetalles.resetValidation();
      this.quantity = null;
      this.price = null;
      if (this.$refs.formFindArticle.validate()) {
        let response = await this.index({
          url: "/api/articulos",
          buscarArticulo: this.article,
          limit: 5
        });

        this.products = response.articulos;
      }
    },

    //Seleccionar Articulo
    selectArticle(article) {
      this.products = [];
      this.article_id = article.id;
      this.article = article.articulo;

      if (article.stock.length > 0) {
        this.stock = article.stock[0].total * 1;
      } else {
        this.stock = 0;
      }
    },

    //LLenar Array de Detalles
    fillDetails() {
      if (this.$refs.formDetalles.validate()) {
        let detail = {
          articulo_id: this.article_id,
          articulo: this.article,
          cantidad: this.quantity,
          lote: this.lote,
          precio: this.price,
          subtotal: this.subtotal
        };

        this.details.push(detail);
        this.form.detalle = this.details;

        this.$refs.formDetalles.reset();
        this.stock = 0;
      }
    },

    //Borrar un Detalle del Array
    removeDetail(detail) {
      let index = this.details.indexOf(detail);
      this.details.splice(index, 1);

      this.form.detalle = this.details;
    },

    //Actualizar cantidad y subtotal de un detalle
    updateDetails() {
      this.details.forEach(function(detail) {
        if (detail.cantidad.length > 0) {
          detail.subtotal = detail.cantidad * detail.precio;
        } else {
          detail.subtotal = 0;
        }
      });

      this.form.detalle = this.details;
    },

    //Metodos Generales

    //Guardar Factura
    saveFactura: async function() {
      //Establecer Mensaje del Snackbar
      this.snackbarText = this.tipo;
      if (this.$refs.formFactura.validate()) {
        //Guardar Factura
        await this.save({ url: "/api/remitos" });
        //Activar Snackbar
        this.snackbar = true;
        //Reset Formularios
        this.details = [];
        await this.$refs.formDetalles.reset();
        await this.$refs.formFactura.reset();
      }
    },

    //Resetear Factura
    cancelFactura: async function() {
      //Reset Formularios
      this.details = [];
      await this.$refs.formFindSupplier.reset();
      await this.$refs.formFindArticle.reset();
      await this.$refs.formDetalles.reset();
      await this.$refs.formFactura.reset();
    }
  }
};
</script>

<style>
</style>
