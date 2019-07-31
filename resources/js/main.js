import Vue from "vue";
import axios from "axios";
import App from "./App.vue";
import router from "./router";
import VueRouterUserRoles from "vue-router-user-roles";
import store from "./store";

// Vuetify
import "./plugins/vuetify";
import "@fortawesome/fontawesome-free/css/all.css";

// Vue Croppa
import Croppa from "vue-croppa";
import "vue-croppa/dist/vue-croppa.css";
Vue.use(Croppa);

// VCharts
import VCharts from "v-charts";
Vue.use(VCharts);

Vue.config.productionTip = false;
Vue.use(VueRouterUserRoles, { router });

let token = localStorage.getItem("accsess_token");
if (token) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + token;
    axios
        .get("/api/user")
        .then(response => {
            Vue.prototype.$user.set({
                role: response.data.rol.role
            });
        })
        .catch(error => {
            commit("fillErrors", error.response.data);
            state.inProcess = false;
            throw new Error(error);
        });
} else {
    Vue.prototype.$user.set({ role: "visitor" });
}

new Vue({
    router,
    store,
    render: function(h) {
        return h(App);
    }
}).$mount("#app");
