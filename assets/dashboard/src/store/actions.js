/* global neveProData */
/* jshint esversion: 6 */
import Vue from 'vue'
import VueResource from 'vue-resource'

Vue.use(VueResource)

const saveModuleSettings = function ({commit, state}, component) {
  state.loading = true
  commit('clearToast')

  let moduleSlug = component.moduleSlug
  let moduleFields = component.fields

  Vue.http({
    url: neveProData.apiRoot + '/save_module_settings',
    method: 'POST',
    headers: {'X-WP-Nonce': neveProData.nonce},
    body: moduleFields,
    responseType: 'json',
    emulateJSON: true,
    params: {
      'module_id': moduleSlug
    }
  }).then(function (response) {
    state.loading = false
    let messageType = 'error-toast'
    if (response.body.success === true) {
      messageType = 'success'
    }
    state.options.modules_options[moduleSlug] = Object.assign({}, moduleFields)
    state.toast = {
      'message': response.body.message,
      'type': messageType
    }
  })
}

const saveOptions = function ({commit, state}, component) {
  state.loading = true
  commit('clearToast')
  Vue.http({
    url: neveProData.apiRoot + '/save_options',
    method: 'POST',
    headers: {'X-WP-Nonce': neveProData.nonce},
    params: {
      'req': 'Save Options'
    },
    body: state.options.modules_status,
    responseType: 'json',
    emulateJSON: true
  }).then(function (response) {
    state.loading = false
    state.currentModuleChanging = null
    if (response.body.success) {
      state.toast = {
        'message': response.body.message,
        'type': 'success'
      }
      console.log('%c Options Saved.', 'color: #59B278')
    } else {
      component.resetModule()
      state.loading = false
      state.currentModuleChanging = null
      state.toast = {
        'message': response.body.message,
        'type': 'error-toast'
      }
    }
  }).catch(function (error) {
    component.resetModule()
    state.loading = false
    state.currentModuleChanging = null
    state.toast = {
      'message': error.body.message,
      'type': 'error-toast'
    }
    console.log('%c Could Not Save Options.', 'color: #E7602A')
  })
}

export default {
  saveOptions,
  saveModuleSettings
}
