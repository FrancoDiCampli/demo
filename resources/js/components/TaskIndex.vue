<template>
  <div>

    <v-layout justify-center wrap>
      <v-flex xs10 sm5 lg3 v-for="task in tasks" :key="task.id" ma-2>
        <v-card height="250" style="overflow: auto;">
          <v-card-title>
            <v-layout space-around>
              <v-flex xs6 mt-2>
                <h2>{{ task.task }}</h2>
              </v-flex>
              <v-flex xs6>
                <v-layout justify-end>
                  <div v-if="task.state">
                    <v-btn flat icon color="success">
                      <v-icon size="medium">fas fa-check</v-icon>
                    </v-btn>
                  </div>
                  <div v-else>
                    <v-btn flat icon color="warning">
                      <v-icon size="medium">fas fa-clock</v-icon>
                    </v-btn>
                  </div>
                  <v-btn flat icon color="error">
                    <v-icon size="medium">fas fa-trash</v-icon>
                  </v-btn>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-card-title>
          <v-card-text>
            {{ task.description }}
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    
  </div>
</template>

<script>



export default {

  name: 'TaskIndex',

  data() {
    return {
      tasks: []
    }
  },

  mounted() {
    this.index();
  },

  methods: {
    index() {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accsess_token');
      axios.get('api/task/index').then(response =>  {
        this.tasks = response.data;
      }).catch(error => {
        console.log(error.response.data);
      });
    }
  }

}

</script>