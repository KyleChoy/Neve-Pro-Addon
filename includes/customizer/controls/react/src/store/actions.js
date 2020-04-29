export default {
  addLayout(layout) {
    const { slug, name } = layout
    return {
      type: 'ADD_LAYOUT',
      payload: { slug, name }
    }
  },
  deleteLayout(slug) {
    return {
      type: 'DELETE_LAYOUT',
      payload: slug
    }
  },
  changeLayout(slug) {
    return {
      type: 'CHANGE_LAYOUT',
      payload: slug
    }
  },
  saveConditions(slug, conditions) {
    return {
      type: 'SAVE_CONDITIONS',
      payload: { slug, conditions }
    }
  },
  saveMods(slug, value) {
    return {
      type: 'SAVE_MODS',
      payload: { slug, value }
    }
  },
  loadDefaults() {
    return {
      type: 'LOAD_VALUES_FOR_DEFAULT'
    }
  }
}
