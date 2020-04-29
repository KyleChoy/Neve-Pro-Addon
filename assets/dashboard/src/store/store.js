/* global neveProData */
/* jshint esversion: 6 */

import Vue from 'vue'
import Vuex from 'vuex'
import actions from './actions'
import mutations from './mutations'

Vue.use(Vuex)

export default new Vuex.Store({
  actions,
  mutations,
  state: {
    loading: false,
    currentModuleChanging: null,
    options: neveProData.options,
    toast: {}
  }
})
