import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth/auth";
import crudx from "./crudx/crudx";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        showClientesDialog: false
    },

    mutations: {
        ClientesDialog(state) {
            state.showClientesDialog = !state.showClientesDialog;
        }
    },

    modules: {
        auth: auth,
        crudx: crudx
    }
});
