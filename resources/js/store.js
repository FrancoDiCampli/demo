import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth/auth";
import crudx from "./crudx/crudx";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        showClientesDialog: false,
        findClienteDialog: false,
    },

    mutations: {
        ClientesDialog(state) {
            state.showClientesDialog = !state.showClientesDialog;
        },

        FindClientesDialog(state) {
            state.findClienteDialog = !state.findClienteDialog;
        },
    },

    modules: {
        auth: auth,
        crudx: crudx
    }
});
