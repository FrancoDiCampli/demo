import axios from 'axios'
import router from '../router'

const state = {

  inProcess: false,
  errors: null,
  data: [],

}

const mutations = {

  fillErrors(state, data) {
    state.errors = data;
  },

  fillData(state, data) {
    state.data = data;
  },

  resetErrors(state) {
    state.errors = null;
  },

  resetData() {
    state.errors = null;
  }

}

const actions = {

  index: function({ state, commit }, params) {
    state.inProcess = true;
    commit('resetErrors');
    axios.get(params.url, { params: params }).then(response => {
      commit('fillData', response.data);
      state.inProcess = false;
    }).catch(error => {
      commit('fillErrors', error.response.data);
      state.inProcess = false;
    });
  },

  redirect: function({dispatch}, params) {
    if(params.goTo) {
      if(typeof params.goTo == 'string') {
        router.push(params.goTo);
        return
      }
    } else if(params.history) {
      if(typeof params.history == 'number') {
        window.history.go(params.history);
        return
      }
    } else if(params.reload) {
      if(typeof params.reload == 'string') {
        let parameters = {
          url: params.reload
        }
        dispatch('index', parameters);
      }
    }
  },

}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}