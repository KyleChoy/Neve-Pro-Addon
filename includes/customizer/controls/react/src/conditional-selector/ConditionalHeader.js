/* jshint esversion: 6 */
/* global wp */
import PropTypes from 'prop-types'
import ConditionalSelectorComponent from './ConditionalSelectorComponent'
import LayoutSelector from './LayoutSelector'
import actions from '../store/actions'
import reducer from '../store/reducer'
import selectors from '../store/selector'

const { compose } = wp.compose
const { registerStore, withSelect } = wp.data
const { Fragment, useState, useEffect } = wp.element
const { __ } = wp.i18n

registerStore( 'neve-store', {
  reducer,
  actions,
  selectors
} )

const ConditionalHeader = ( props ) => {
  const [addingLayout, setAddingLayout] = useState( false )
  const [boundNotification, bindNotification] = useState( false )
  const [currentSection, setCurrentSection] = useState( false )
  const [currentPanel, setCurrentPanel] = useState( false )
  const [currentHeader, setCurrentHeader] = useState( 'Default' )
  useEffect( () => {
    wp.customize.state( 'expandedSection' ).bind( ( sectionVisible ) => {
      if ( wp.customize.control('neve_global_header').setting.get() ) {
        return false
      }
      const section = sectionVisible.id || false
      setCurrentSection( section )
      if ( section === 'neve_pro_global_header_settings' || currentPanel !== 'hfg_header' ) {
        wp.customize.notifications.remove( 'neve-current-header' )
      } else if ( currentPanel === 'hfg_header' ) {
        wp.customize.notifications.remove( 'neve-current-header' )
        addNotification()
      }
    } )
    wp.customize.state( 'expandedPanel' ).bind( ( paneVisible ) => {
      if ( wp.customize.control('neve_global_header').setting.get() ) {
        return false
      }
      const panel = paneVisible.id || false
      setCurrentPanel( panel )

      if ( currentSection === 'neve_pro_global_header_settings' || panel !== 'hfg_header' ) {
        wp.customize.notifications.remove( 'neve-current-header' )
      } else {
        wp.customize.notifications.remove( 'neve-current-header' )
        addNotification()
      }
    } )

    wp.customize.bind( 'change', (setting) => {
      if ( setting.id !== 'neve_global_header' ) {
        return false
      }

      if ( setting.get() ) {
        wp.customize.notifications.remove( 'neve-current-header' )
        hideBuilderNotification()
      }
    } )
  } )

  function addNotification() {
    if ( props.headerLayoutsCount === 1 ) {
      wp.customize.notifications.remove( 'neve-current-header' )
      return false
    }
    const message = __( 'You are customizing the', 'neve' ) +
            ' <a>' + currentHeader + '</a> ' +
            __( 'Header', 'neve' )
    wp.customize.notifications.add( 'neve-current-header', new wp.customize.Notification( 'info', {
      message,
      type: 'info',
      code: 'neve-current-header',
      render: function () {
        const li = wp.customize.Notification.prototype.render.call( this )
        const link = li.find( 'a' )
        link.on( 'click', function ( event ) {
          event.preventDefault()
          wp.customize.control( 'neve_header_conditional_selector' ).focus()
        } )
        return li
      }
    } ) )
  }

  function changeBuilderNotification(name) {
    const builderNotification = document.querySelector( '.hfg--cb-conditional-header' )
    if ( !builderNotification ) {
      return false
    }
    const link = builderNotification.querySelector( 'a' )
    if ( !boundNotification ) {
      link.addEventListener( 'click', function ( e ) {
        e.preventDefault()
        wp.customize.control( 'neve_header_conditional_selector' ).focus()
      } )
      bindNotification( true )
    }
    builderNotification.classList.remove( 'hidden' )
    link.innerHTML = name
    if ( props.headerLayoutsCount === 1 ) {
      builderNotification.classList.add( 'hidden' )
    }
  }

  const hideBuilderNotification = () => {
    const builderNotification = document.querySelector( '.hfg--cb-conditional-header' )
    if ( builderNotification ) builderNotification.classList.add( 'hidden' )
  }

  const onLayoutChange = ( val, name ) => {
    setCurrentHeader(name)
    changeBuilderNotification(name)
  }

  if ( props.headerLayoutsCount <= 1 ) {
    hideBuilderNotification()
  }

  return (
    <Fragment>
      <LayoutSelector
        onToggleAddingLayout={() => {
          setAddingLayout( !addingLayout )
        }}
        onLayoutChange={( val, name ) => {
          onLayoutChange( val, name )
        }}
        control={props.control}
      />
      {!addingLayout && <ConditionalSelectorComponent control={props.control} />}
      {props.currentLayoutSlug === 'default' && !addingLayout && (
        <p>
          {
            // eslint-disable-next-line max-len
            __( 'You can create new headers and set them on a particular page/post, taxonomy, post types and more with display rules.', 'neve' )
          }
        </p>
      )}
    </Fragment>
  )
}

ConditionalHeader.propTypes = {
  control: PropTypes.object.isRequired,
  headerLayoutsCount: PropTypes.number.isRequired,
  currentLayoutSlug: PropTypes.string.isRequired
}

export default compose(
  withSelect( ( select ) => {
    const {
      getHeaderLayouts,
      getCurrentLayout
    } = select( 'neve-store' )
    const layouts = getHeaderLayouts()
    return {
      headerLayoutsCount: Object.keys( layouts ).length,
      currentLayoutSlug: getCurrentLayout()
    }
  } ) )( ConditionalHeader )
