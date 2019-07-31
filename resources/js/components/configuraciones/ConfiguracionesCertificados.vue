<template>
    <div>
        <v-snackbar color="primary" v-model="snackbarCertificados" :timeout="6000" right top>
            Archivos Cargados
            <v-btn color="white" flat @click="snackbarAvanzada = false" icon>
                <v-icon>fas fa-times</v-icon>
            </v-btn>
        </v-snackbar>
        <v-layout justify-space-between wrap>
            <v-flex xs12 sm6>
                <div class="fileContainer">
                    <div class="fileButton">
                        Archivo .key
                        <input
                            class="fileInput"
                            type="file"
                            id="fileKey"
                            ref="fileKey"
                            v-on:change="setFileKey()"
                        />
                    </div>
                    <transition name="card">
                        <div v-if="!valueKey">
                            <v-card
                                class="mx-auto fileContent"
                                color="primary"
                                dark
                                v-show="fileKey != null"
                            >
                                <v-card-text>
                                    <v-icon class="fileIcon">fas fa-file fa-4x</v-icon>
                                </v-card-text>
                                <v-card-text>
                                    <div class="fileName">{{ keyName }}</div>
                                </v-card-text>
                            </v-card>
                        </div>
                        <div v-else>
                            <v-card class="mx-auto fileContent" color="primary" dark>
                                <v-card-text>
                                    <v-icon class="fileIcon">fas fa-file fa-4x</v-icon>
                                </v-card-text>
                                <v-card-text>
                                    <div class="fileName">archivo.key</div>
                                </v-card-text>
                            </v-card>
                        </div>
                    </transition>
                </div>
            </v-flex>
            <v-flex xs12 sm6>
                <div class="fileContainer">
                    <div class="fileButton">
                        Archivo .crt
                        <input
                            class="fileInput"
                            type="file"
                            id="fileCert"
                            ref="fileCert"
                            v-on:change="setFileCert()"
                        />
                    </div>
                    <transition name="card">
                        <div v-if="!valueCert">
                            <v-card
                                class="mx-auto fileContent"
                                color="primary"
                                dark
                                v-show="fileCert != null"
                            >
                                <v-card-text>
                                    <v-icon class="fileIcon">fas fa-file fa-4x</v-icon>
                                </v-card-text>
                                <v-card-text>
                                    <div class="fileName">{{ keyCert }}</div>
                                </v-card-text>
                            </v-card>
                        </div>
                        <div v-else>
                            <v-card class="mx-auto fileContent" color="primary" dark>
                                <v-card-text>
                                    <v-icon class="fileIcon">fas fa-file fa-4x</v-icon>
                                </v-card-text>
                                <v-card-text>
                                    <div class="fileName">archivo.cert</div>
                                </v-card-text>
                            </v-card>
                        </div>
                    </transition>
                </div>
            </v-flex>
        </v-layout>
        <br />
        <v-layout justify-center>
            <v-btn
                :loading="process"
                :disabled="fileKey != null && fileCert != null && process != true ? false : true"
                color="primary"
                @click="submitFile()"
            >Cargar</v-btn>
        </v-layout>
    </div>
</template>

<script>
import axios from "axios";
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("accsess_token");

export default {
    name: "ConfiguracionesCertificados",

    props: ["valueCert", "valueKey"],

    data() {
        return {
            fileKey: null,
            keyName: "",
            fileCert: null,
            keyCert: "",
            process: false,
            snackbarCertificados: false
        };
    },

    methods: {
        submitFile() {
            if (this.fileKey != null && this.fileCert != null) {
                this.process = true;
                var formData = new FormData();
                formData.append("key", this.fileKey);
                formData.append("cert", this.fileCert);

                axios
                    .post("/api/config/update/cert", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    })
                    .then(response => {
                        this.snackbarCertificados = true;
                        this.process = false;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        setFileKey() {
            this.fileKey = this.$refs.fileKey.files[0];
            this.keyName = this.fileKey.name;
        },

        setFileCert() {
            this.fileCert = this.$refs.fileCert.files[0];
            this.keyCert = this.fileCert.name;
        }
    }
};
</script>

<style>
.fileContainer {
    padding: 8px 32px;
    width: auto;
}

.fileButton {
    overflow: hidden;
    display: inline-block;
    position: relative;
    cursor: pointer;
    width: 100%;
    height: 35px;
    line-height: 35px;
    padding: 0 1.5rem;
    color: #26a69a;
    font-size: 16px;
    font-weight: 600;
    font-family: "Roboto", sans-serif;
    letter-spacing: 0.8px;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    white-space: nowrap;
    outline: none;
    border: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border-radius: 2px;
    transition: all 0.3s ease-out;
    border: solid 0.5px #26a69a;
}

.fileInput {
    cursor: pointer;
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    font-size: 50px;
    opacity: 0;
    -moz-opacity: 0;
    filter: Alpha(opacity=0);
}

.fileContent {
    width: 100%;
    height: 150px;
    margin: 6px;
    text-align: center;
}

.fileIcon {
    margin-top: 16px;
}

.fileName {
    font-size: 16px;
    margin-top: -16px;
}

.card-enter-active,
.card-leave-active {
    transition: all 5s;
}
.card-enter,
.card-leave-to {
    opacity: 0;
    transform: translateY(-30px);
    transition: all 5s;
}
</style>