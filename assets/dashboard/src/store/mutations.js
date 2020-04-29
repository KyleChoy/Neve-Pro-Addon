/* jshint esversion: 6 */
const toggleModule = (state, slug) => {
  let deactivated = state.options.modules_status
  let status = state.options.modules_status[slug] || null

  if (status === 'enabled') {
    deactivated[slug] = 'disabled'
    return false
  }
  deactivated[slug] = 'enabled'
  state.options.modules_status = deactivated
}

const clearToast = (state) => {
  state.toast = {}
}

export default {
  toggleModule,
  clearToast
}
