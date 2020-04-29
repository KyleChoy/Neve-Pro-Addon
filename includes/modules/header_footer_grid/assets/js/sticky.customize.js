( function() {
  /**
   * Run function when customizer is ready.
   */
  wp.customize.bind( 'ready', function() {
    jQuery.each( stickyRows, function(control, dependentControl) {
      if ( wp.customize.control( control ).setting.get() === false ) {
        wp.customize.control( dependentControl ).toggle( false )
      } else {
        wp.customize.control( dependentControl ).toggle( true )
      }
      /**
       * Run function on setting change of control.
       */
      wp.customize.control( control ).setting.bind( function(value) {
        if ( value === false ) {
          wp.customize.control( dependentControl ).toggle( false )
        } else {
          wp.customize.control( dependentControl ).toggle( true )
        }
      } )

      wp.customize.control( dependentControl ).setting.bind( function(value) {
        if( wp.customize.previewer.targetWindow.get() === null ) {
          return false;
        }
        if ( value === false ) {
          wp.customize.previewer.targetWindow.get().scrollTo( 0, 0 )
        } else {
          wp.customize.previewer.targetWindow.get().scrollTo( 0, 100 )
        }
      } )
    } )
  } )
} )()
