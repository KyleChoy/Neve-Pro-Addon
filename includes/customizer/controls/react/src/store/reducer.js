/* global wp, NeveProReactCustomize  */
import { maybeParseJson } from '../common'

const { __ } = wp.i18n
const initialState = {
  currentHeaderLayout: 'default',
  headerLayouts: {
    default: __( 'Default', 'neve' )
  },
  conditions: {
    default: [[{ root: '', condition: '===', end: '' }]]
  },
  themeMod: {
    default: NeveProReactCustomize.currentValues
  },
  defaultOldValues: {}
}

const { headerLayouts } = NeveProReactCustomize
const existingLayouts = {}
const existingConditions = {}
const existingThemeMod = {}

Object.keys( headerLayouts )
  .map( (key) => {
    existingLayouts[key] = headerLayouts[key].label

    existingConditions[key] = headerLayouts[key].conditions
      ? headerLayouts[key].conditions
      : [[{ root: '', condition: '===', end: '' }]]

    existingThemeMod[key] = headerLayouts[key].mods
      ? { ...headerLayouts[key].mods }
      : {}
  } )

initialState.headerLayouts = {
  ...initialState.headerLayouts,
  ...existingLayouts
}

initialState.conditions = {
  ...initialState.conditions,
  ...existingConditions
}

initialState.themeMod = {
  ...initialState.themeMod,
  ...existingThemeMod
}

NeveProReactCustomize.headerControls.map(
  (controlId) => {
    if ( !wp.customize.control( controlId ) ) return false
    initialState.themeMod.default[controlId] = wp.customize.control( controlId )
      .setting
      .get()
  }
)

const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'ADD_LAYOUT': {
      const { name, slug } = action.payload
      return {
        ...state,
        headerLayouts: {
          ...state.headerLayouts,
          [slug]: name
        },
        conditions: {
          ...state.conditions,
          [slug]: [[{ root: '', condition: '===', end: '' }]]
        },
        themeMod: {
          ...state.themeMod,
          [slug]: state.themeMod.default
        }
      }
    }
    case 'DELETE_LAYOUT': {
      const { headerLayouts, conditions, themeMod } = state
      if ( headerLayouts[action.payload] ) {
        Reflect.deleteProperty( headerLayouts, action.payload )
      }
      if ( conditions[action.payload] ) {
        Reflect.deleteProperty( conditions, action.payload )
      }
      if ( themeMod[action.payload] ) {
        Reflect.deleteProperty( themeMod, action.payload )
      }
      return {
        ...state,
        currentHeaderLayout: 'default',
        conditions,
        headerLayouts,
        themeMod
      }
    }
    case 'CHANGE_LAYOUT':
      return {
        ...state,
        currentHeaderLayout: action.payload
      }
    case 'SAVE_CONDITIONS': {
      const { slug, conditions } = action.payload

      return {
        ...state,
        conditions: { ...state.conditions, [slug]: conditions }
      }
    }
    case 'SAVE_MODS': {
      const { slug, value } = action.payload

      return {
        ...state,
        themeMod: { ...state.themeMod, [slug]: { ...value } }
      }
    }
    case 'LOAD_VALUES_FOR_DEFAULT': {
      const mods = NeveProReactCustomize.headerControls
      const values = {}

      mods.map( (mod) => {
        if ( typeof wp.customize.control( mod ) !== 'undefined' ) {
          values[mod] = maybeParseJson(
            wp.customize.control( mod ).setting.get()
          )
        }
      } )
      return {
        ...state,
        themeMod: { ...state.themeMod, default: values }
      }
    }
  }
  return state
}

export default reducer
