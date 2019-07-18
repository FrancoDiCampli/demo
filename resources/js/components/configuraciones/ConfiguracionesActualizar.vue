<template>
    <div>
        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="uploadOptions"></vue-dropzone>
    </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

import { mapActions } from "vuex";
export default {
    name: "ConfiguracionesActualizar",

    data: function() {
        return {
            uploadOptions: {}
        };
    },

    components: {
        vueDropzone: vue2Dropzone
    },

    created() {
        this.getToken();
    },

    methods: {
        ...mapActions("crudx", ["save"]),

        getToken() {
            let token = localStorage.getItem("accsess_token");
            this.uploadOptions = {
                url: "/api/configuracion",
                headers: {
                    Authorization: "Bearer " + token
                }
            };
        },

        saveFile() {}
    }
};
</script>

<style>
</style>
