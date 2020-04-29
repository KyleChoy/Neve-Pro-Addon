/* jshint esversion: 6 */
/* global wp, NeveProReactCustomize */
import PropTypes from 'prop-types'
import { maybeParseJson } from '../common'

const { Component, Fragment } = wp.element
const { Button } = wp.components
const { __ } = wp.i18n
const { withSelect, withDispatch } = wp.data
const { compose } = wp.compose

class LayoutSelector extends Component {
  constructor(props) {
    super( props )

    this.state = {
      isAddingLayout: false,
      newLayout: '',
      inChangeset: false
    }

    this.handleUpdateLayout = this.handleUpdateLayout.bind( this )
    this.addLayout = this.addLayout.bind( this )
    this.selectNewLayout = this.selectNewLayout.bind( this )
    this.deleteLayout = this.deleteLayout.bind( this )
  }

  componentDidMount() {
    // Even more mental gymnastics to trigger a change for this control.
    wp.customize.bind( 'saved', () => {
      this.setState( { inChangeset: false } )
    } )

    window.addEventListener( 'load', () => {
      this.props.loadDefaults()
    } )

    wp.customize.bind( 'change', (setting) => {
      const { control, currentHeaderLayout, themeMods } = this.props

      if ( setting.id === 'neve_global_header' ) {
        this.selectNewLayout( 'default' )
        return false
      }

      if ( !NeveProReactCustomize.headerControls.includes( setting.id ) ) {
        return false
      }

      const oldConfig = control.setting.get()
      if ( !oldConfig.themeMods ) {
        oldConfig.themeMods = {}
      }

      if ( !oldConfig.themeMods[currentHeaderLayout] ) {
        oldConfig.themeMods[currentHeaderLayout] = {}
      }

      const previousThemeMods = oldConfig.themeMods[currentHeaderLayout]

      control.setting.set( {
        ...oldConfig,
        themeMods: {
          ...( oldConfig.themeMods || {} ),
          [currentHeaderLayout]: {
            ...( previousThemeMods ),
            [setting.id]: maybeParseJson( setting.get() )
          }
        }
      } )

      this.props.saveMods( currentHeaderLayout, {
        ...themeMods[currentHeaderLayout],
        [setting.id]: maybeParseJson( setting.get() )
      } )

      if ( this.state.inChangeset ) {
        return false
      }

      const oldValue = control.setting.get()
      control.setting.set( {} )
      control.setting.set( oldValue )
      this.setState( { inChangeset: true } )
    } )

    /**
     * Listen for preset changes and treat them accordingly.
     */
    document.addEventListener( 'neve-preset-changed', (e) => {
      if ( !e.detail ) return false
      if ( !e.detail.themeMods ) return false
      const changePreset = async() => {
        const { control, currentHeaderLayout } = this.props
        const presetMods = e.detail.themeMods
        const oldConfig = control.setting.get()
        const previousThemeMods = oldConfig.themeMods[currentHeaderLayout]
        Object.keys( presetMods ).map( (key) => {
          presetMods[key] = maybeParseJson( presetMods[key] )
        } )
        const newMods = { ...previousThemeMods, ...presetMods }

        control.setting.set( {
          ...oldConfig,
          themeMods: {
            ...( oldConfig.themeMods || {} ),
            [currentHeaderLayout]: newMods
          }
        } )
        this.props.saveMods( currentHeaderLayout, newMods )

        console.log(this.props.themeMods[currentHeaderLayout])
      }

      changePreset().then( () => {
        this.selectNewLayout( this.props.currentHeaderLayout )
      } )
    } )
  }

  addLayout(name) {
    this.setState( {
      isAddingLayout: false,
      newLayout: ''
    } )
    this.props.onToggleAddingLayout( false )
    const layoutSnakeCase = 'hfgcl_' + name.trim()
      .toLowerCase()
      .replace( /[^a-zA-Z0-9]+/g, '-' )

    const { headerLayouts, control } = this.props

    if ( headerLayouts[layoutSnakeCase] ) {
      console.log( `Already has layout named ${name}(${layoutSnakeCase})` )
      return false
    }

    const toDelete = control.setting.get().delete || []
    const alreadyAddable = control.setting.get().add || {}
    toDelete.splice( toDelete.indexOf( layoutSnakeCase ), 1 )

    const addLayout = async() => {
      this.props.addLayout( { slug: layoutSnakeCase, name: name.trim() } )
      control.setting.set( {
        ...control.setting.get(),
        delete: toDelete,
        add: { ...alreadyAddable, [layoutSnakeCase]: name.trim() }
      } )
    }

    addLayout().then( () => {
      this.selectNewLayout( layoutSnakeCase )
    } )
  }

  handleUpdateLayout(e) {
    this.setState( { newLayout: e.target.value } )
  }

  selectNewLayout(layout) {
    const { control, themeMods, headerLayouts } = this.props
    this.props.onLayoutChange( layout, headerLayouts[layout] )
    const changeLayout = async(newLayout) => {
      this.props.changeLayout( newLayout )
      control.setting.set( {
        ...control.setting.get(),
        layout: newLayout,
        themeMods
      } )
      return newLayout
    }
    changeLayout( layout ).then( (newLayout) => {
      document.dispatchEvent( new CustomEvent( 'neve-changed-builder-value', {
        detail: {
          value: themeMods[newLayout].hfg_header_layout,
          id: 'header'
        }
      } ) )

      Object.keys( themeMods[newLayout] ).map( (key) => {
        if ( key === 'hfg_header_layout' ) {
          return false
        }

        if ( !wp.customize.control( key ) ) {
          return false
        }

        // Switch core text, textarea and select control types to proper values.
        if ( ['text', 'textarea', 'select'].includes(
          wp.customize.control( key ).params.type ) ) {
          wp.customize.control( key ).setting.set( themeMods[newLayout][key] )
          return false
        }

        // Dispatch event for controls listening for the new layout values.
        document.dispatchEvent(
          new CustomEvent( 'neve-changed-customizer-value', {
            detail: {
              value: themeMods[newLayout][key] || '',
              id: key
            }
          } ) )
      } )
    } ).then( () => {
      wp.customize.previewer.trigger( 'refresh' )
    } )
  }

  deleteLayout(layout) {
    this.props.deleteLayout( layout )

    const { control } = this.props
    const alreadyDeleteable = control.setting.get().delete || []
    const toAdd = control.setting.get().add || []
    delete toAdd[layout]
    control.setting.set( {
      ...control.setting.get(),
      delete: [...alreadyDeleteable, layout],
      add: toAdd
    } )
    this.selectNewLayout( 'default' )
  }

  render() {
    const { isAddingLayout, newLayout } = this.state
    const { headerLayouts, currentHeaderLayout } = this.props
    return (
      <Fragment>
        <label className='customize-control-title'>
          {__( 'Select Header', 'neve' )}
        </label>
        <div className='select-wrap layout-selector'>
          <select
            disabled={isAddingLayout}
            value={currentHeaderLayout}
            onChange={(event) => {
              this.selectNewLayout( event.target.value )
            }}
          >
            {
              Object.keys( headerLayouts )
                .map( (key) => {
                  return (
                    <option
                      value={key}
                      key={key}
                    >{headerLayouts[key]}
                    </option>
                  )
                } )
            }
          </select>
          {currentHeaderLayout !== 'default' && (
            <Button
              type='button'
              isDefault
              isDestructive
              disabled={isAddingLayout}
              className='remove-layout'
              onClick={() => { this.deleteLayout( currentHeaderLayout ) }}
            >
              {__( 'Remove', 'neve' )}
            </Button> )}
        </div>
        {!isAddingLayout && (
          <Button
            isDefault
            isLink
            className='layout-add-button'
            onClick={(e) => {
              e.preventDefault()
              this.setState( { isAddingLayout: true } )
              this.props.onToggleAddingLayout( true )
            }}
          >
            + {__( 'Add New Header', 'neve' )}
          </Button> )}
        {
          isAddingLayout && (
            <form
              className='add-layout-form'
              onSubmit={() => {
                this.addLayout( newLayout )
              }}
            >
              <input
                name='layout-slug'
                value={newLayout}
                onChange={this.handleUpdateLayout}
                type='text'
                placeholder={__( 'Header Name', 'neve' )}
              />
              <Button
                isDefault
                onClick={() => {
                  this.setState( { isAddingLayout: false, newLayout: '' } )
                  this.props.onToggleAddingLayout( false )
                }}
              >{__( 'Cancel', 'neve' )}
              </Button>
              <Button
                className='add'
                type='button'
                disabled={newLayout.length < 1}
                isPrimary
                onClick={() => {
                  this.addLayout( newLayout )
                }}
              >{__( 'Add', 'neve' )}
              </Button>
            </form> )
        }
      </Fragment>
    )
  }
}

LayoutSelector.propTypes = {
  control: PropTypes.object.isRequired,
  currentHeaderLayout: PropTypes.string.isRequired,
  headerLayouts: PropTypes.object.isRequired,
  themeMods: PropTypes.object.isRequired,
  onToggleAddingLayout: PropTypes.func.isRequired,
  onLayoutChange: PropTypes.func.isRequired,
  changeLayout: PropTypes.func.isRequired,
  deleteLayout: PropTypes.func.isRequired,
  addLayout: PropTypes.func.isRequired,
  saveMods: PropTypes.func.isRequired,
  loadDefaults: PropTypes.func.isRequired
}

export default compose(
  withSelect( (select) => {
    const {
      getHeaderLayouts,
      getCurrentLayout,
      getThemeMods
    } = select( 'neve-store' )

    return {
      currentHeaderLayout: getCurrentLayout(),
      headerLayouts: getHeaderLayouts(),
      themeMods: getThemeMods()
    }
  } ),
  withDispatch( (dispatch) => {
    const {
      changeLayout,
      deleteLayout,
      addLayout,
      saveMods,
      loadDefaults
    } = dispatch( 'neve-store' )
    return {
      changeLayout: (layout) => {
        changeLayout( layout )
      },
      deleteLayout: (layout) => {
        deleteLayout( layout )
      },
      addLayout: (layout) => {
        addLayout( layout )
      },
      saveMods: (slug, value) => {
        saveMods( slug, value )
      },
      loadDefaults: () => {
        loadDefaults()
      }
    }
  } )
)( LayoutSelector )
