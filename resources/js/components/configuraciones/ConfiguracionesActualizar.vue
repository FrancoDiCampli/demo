<template>
    <div>
        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="uploadOptions"></vue-dropzone>
        <br />
        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
        <v-btn @click="submitFile()">upload</v-btn>
    </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

import { mapActions } from "vuex";

import axios from "axios";
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("accsess_token");
export default {
    name: "ConfiguracionesActualizar",

    data: function() {
        return {
            uploadOptions: {},
            file: ""
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

        submitFile() {
            let formData = new FormData();
            formData.append("file", this.file);

            axios
                .post("/api/configuracion", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(response.data);
                });
        },

        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        }
    }
};
</script>

<style>
</style>
