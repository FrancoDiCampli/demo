<template>
    <div>
        <br>
        <br>
        <v-layout justify-center>
            <v-flex xs10 lg8>
                <v-layout justify-center>
                    <!-- Register card -->
                    <v-card style="width: 400px; min-height: 350px; height: auto;">
                        <v-card-title class="primary">
                            <h1 class="white--text">Sign Up</h1>
                        </v-card-title>
                        <v-card-text style="padding: 0;">
                            <v-layout wrap align-center>
                                <!-- Register Progress -->
                                <v-flex xs12 pa-3 v-show="inProcess">
                                    <v-layout justify-center style="margin-top: 80px;">
                                        <v-progress-circular
                                            :size="70"
                                            :width="7"
                                            color="primary"
                                            indeterminate
                                        ></v-progress-circular>
                                    </v-layout>
                                </v-flex>

                                <!-- Register Form -->
                                <v-flex xs12 pa-3 v-show="!inProcess">
                                    <v-form
                                        ref="register_form"
                                        @submit.prevent="registerValidate()"
                                    >
                                        <RegisterForm></RegisterForm>
                                        <v-layout justify-center wrap>
                                            <v-btn to="/login" outline color="primary">Log In</v-btn>
                                            <v-btn type class="elevation-0" color="primary">Sign Up</v-btn>
                                        </v-layout>
                                    </v-form>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import RegisterForm from "../components/auth_components/RegisterForm.vue";
import { mapState, mapActions } from "vuex";

export default {
    name: "Register",

    components: {
        RegisterForm
    },

    computed: {
        ...mapState("auth", ["inProcess"])
    },

    methods: {
        ...mapActions("auth", ["register", "login"]),

        registerValidate: async function() {
            if (this.$refs.register_form.validate()) {
                await this.register();
                await this.login();
                this.$router.push("/account");
            }
        }
    }
};
</script>
