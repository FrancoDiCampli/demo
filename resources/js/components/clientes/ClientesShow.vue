<template>
    <div>
        <v-layout justify-center wrap>
            <v-flex xs12>
                <v-layout justify-center>
                    <v-avatar class="profile" size="86">
                        <span class="display-1">{{ clientProfile }}</span>
                    </v-avatar>
                </v-layout>
            </v-flex>
            <v-flex xs12>
                <br>
                <h1 class="text-xs-center primary--text">{{ showData.razonsocial }}</h1>
            </v-flex>
            <v-flex xs12>
                <h3 class="text-xs-center primary--text">{{ showData.documentounico }}</h3>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "ClientesShow",

    computed: {
        ...mapState("crudx", ["showData"]),

        clientProfile() {
            if (this.showData.razonsocial) {
                let arrayname = this.showData.razonsocial.split(" ");
                let profile = "";

                for (let i = 0; i < 2; i++) {
                    for (let e = 0; e < 1; e++) {
                        profile = profile + arrayname[i][e];
                    }
                }

                return profile;
            }
        }
    }
};
</script>

<style>
.profile {
    border: solid 3px #26a69a;
    background-color: rgba(65, 184, 131, 0.25);
}

.profile span {
    color: #26a69a;
}
</style>
