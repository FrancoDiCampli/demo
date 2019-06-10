<template>
  <div>
    <v-btn
      dark
      fab
      fixed
      right
      bottom
      @click="createMarcasDialog = !createMarcasDialog"
      color="primary"
    >
      <v-icon>fas fa-plus</v-icon>
    </v-btn>

    <v-dialog v-model="createMarcasDialog" width="750" persistent>
      <v-card>
        <v-card-text>
          <v-layout justify-space-between>
            <h2>Nueva Marca</h2>
            <v-btn @click="createMarcasDialog = false; " flat icon style="margin: 0; padding: 0;">
              <v-icon>fas fa-times</v-icon>
            </v-btn>
          </v-layout>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <template>
            <div class="loading" v-show="inProcess">
              <v-layout justify-center>
                <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
              </v-layout>
            </div>
          </template>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "Marcas",

  data() {
    return {
      createMarcasDialog: false
    };
  },

  computed: {
    ...mapState("crudx", ["inProcess"])
  },

  components: {},

  methods: {
    ...mapActions("crudx", ["index", "save"]),

    saveCliente: async function() {}
  }
};
</script>

<style>
</style>
