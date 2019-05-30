<template>
    <v-app>
        <!-- Toolbar | Navbar -->
        <v-toolbar color="secondary" class="elevation-0">
            <v-toolbar-title @click="$router.push('/')" style="cursor: pointer;">Gepetto</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn to="/login" flat v-show="token == null">Login</v-btn>
                <v-btn to="/register" flat v-show="token == null">Sign Up</v-btn>
                <v-btn to="/roles" flat v-show="token !== null">Roles</v-btn>
                <v-btn to="/users" flat v-show="token !== null">Users</v-btn>
                <v-btn @click="exit()" flat v-show="token !== null">Logout</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <br>
        <v-content>
            <router-view/>
        </v-content>
    </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";

export default {
    name: "App",

    computed: {
        ...mapState("auth", ["token"])
    },

    methods: {
        ...mapActions("auth", ["logout"]),
        exit: async function() {
            await this.logout();
            this.$router.push("/");
        }
    }
};
</script>

