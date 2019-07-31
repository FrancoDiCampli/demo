<template>
    <div>
        <br />
        <br />
        <v-layout wrap>
            <v-flex xs12>
                <v-layout justify-center>
                    <img src="images/logo.png" width="300" height="300" />
                </v-layout>
            </v-flex>
            <v-flex xs12>
                <br />
                <h2
                    class="text-xs-center font-weight-light"
                >Es necesario establecer algunas configuraciones antes de comenzar a usar el sistema. Por favor, comuníquese con su administrador para configurar todo lo necesario.</h2>
            </v-flex>
        </v-layout>
        <br />
        <v-layout justify-center>
            <v-btn @click="exit()" color="primary" outline>Cerrar Sesión</v-btn>
        </v-layout>
    </div>
</template>

<script>
// Vuex
import { mapActions } from "vuex";

export default {
    name: "Unauthenticated",

    methods: {
        ...mapActions("auth", ["logout"]),

        exit: async function() {
            await this.logout();
            this.$router.push("/");
            this.$user.set({ role: "visitor" });
        }
    }
};
</script>
