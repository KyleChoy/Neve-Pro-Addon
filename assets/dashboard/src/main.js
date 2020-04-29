/* jshint esversion: 6 */
import Vue from 'vue'
import App from './App.vue'
import store from './store/store.js'
import ToggleButton from 'vue-js-toggle-button'

Vue.use(ToggleButton)

window.addEventListener('load', function () {
  new Vue({ // eslint-disable-line no-new
    el: '#neve-pro-dashboard',
    store,
    render: (h) => h(App)
  })
})

