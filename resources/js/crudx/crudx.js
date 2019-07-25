import router from "../router";

import axios from "axios";
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("accsess_token");

const state = {
    inProcess: false,
    form: {},
    errors: null,
    data: [],
    showData: [],
    notifications: []
};

const mutations = {
    fillForm(state, form) {
        state.form = form;
    },

    fillErrors(state, errors) {
        state.errors = errors;
    },

    fillData(state, data) {
        state.data = data;
    },

    fillShowData(state, showData) {
        state.showData = showData;
    },

    fillNotifications(state, notifications) {
        state.notifications = notifications;
    },

    resetForm(state) {
        state.form = {};
    },

    resetErrors(state) {
        state.errors = null;
    },

    resetData() {
        state.data = [];
    },

    resetShowData() {
        state.showData = [];
    },

    resetNotifications() {
        state.notifications = [];
    },

    resetAll() {
        state.form = {};
        state.errors = null;
        state.data = [];
        state.showData = [];
        state.notifications = [];
    }
};

const actions = {
    index: function({ state, commit, dispatch }, params) {
        state.inProcess = true;
        commit("resetErrors");
        return new Promise(resolve => {
            axios
                .get(params.url, { params: params })
                .then(response => {
                    commit("fillData", response.data);
                    state.inProcess = false;
                    dispatch("getNotifications");
                    resolve(response.data);
                })
                .catch(error => {
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        });
    },

    show: function({ state, commit, dispatch }, params) {
        state.inProcess = true;
        commit("resetErrors");
        return new Promise(resolve => {
            axios
                .get(params.url, { params: params })
                .then(response => {
                    commit("fillShowData", response.data);
                    state.inProcess = false;
                    dispatch("getNotifications");
                    resolve(response.data);
                })
                .catch(error => {
                    if (
                        error.response.status == 403 ||
                        error.response.status == 403
                    ) {
                        router.push("/account");
                    }
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        });
    },

    save: function({ state, commit, dispatch }, params) {
        state.inProcess = true;
        commit("resetErrors");
        return new Promise(resolve => {
            axios
                .post(params.url, state.form)
                .then(response => {
                    commit("resetForm");
                    state.inProcess = false;
                    dispatch("getNotifications");
                    resolve(response.data);
                })
                .catch(error => {
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        });
    },

    edit: function({ commit }, params) {
        return new Promise(resolve => {
            commit("fillForm", params.data);
            resolve();
        });
    },

    update: function({ state, commit, dispatch }, params) {
        state.inProcess = true;
        commit("resetErrors");
        return new Promise(resolve => {
            axios
                .put(params.url, state.form)
                .then(response => {
                    commit("resetForm");
                    state.inProcess = false;
                    dispatch("getNotifications");
                    resolve(response.data);
                })
                .catch(error => {
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        });
    },

    destroy: function({ commit, dispatch }, params) {
        state.inProcess = true;
        commit("resetErrors");
        return new Promise(resolve => {
            axios
                .delete(params.url)
                .then(response => {
                    state.inProcess = false;
                    dispatch("getNotifications");
                    resolve(response.data);
                })
                .catch(error => {
                    commit("fillErrors", error.response.data);
                    state.inProcess = false;
                    throw new Error(error);
                });
        });
    },

    getNotifications: function({ commit }) {
        return new Promise(resolve => {
            axios
                .get("/api/notifications")
                .then(response => {
                    commit("fillNotifications", response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    throw new Error(error);
                });
        });
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
