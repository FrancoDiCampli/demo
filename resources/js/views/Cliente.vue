<template>
    <div>
        <v-btn
            dark
            fab
            fixed
            right
            bottom
            @click="createClientesDialog = !createClientesDialog"
            color="primary"
        >
            <v-icon>fas fa-plus</v-icon>
        </v-btn>

        <v-dialog v-model="createClientesDialog" width="750" persistent>
            <v-card>
                <v-card-text>
                    <h2>Nuevo CLiente</h2>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="roleForm" @submit.prevent="saveCliente">
                        <br>
                        <ClientesForm></ClientesForm>
                        <v-layout justify-end>
                            <v-btn
                                @click="createClientesDialog = false"
                                outline
                                color="error"
                            >Cancelar</v-btn>
                            <v-btn class="elevation-0" type="submit" color="primary">Guardar</v-btn>
                        </v-layout>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <ClientesIndex></ClientesIndex>
    </div>
</template>

<script>
import ClientesIndex from "../components/clientes/ClientesIndex.vue";
import ClientesForm from "../components/clientes/ClientesForm.vue";
export default {
    name: "Cliente",

    data() {
        return {
            createClientesDialog: false
        };
    },

    components: {
        ClientesIndex,
        ClientesForm
    }
};
</script>

<style>
</style>
