( function( $ ) {

	wp.customize.bind( 'ready', function() {

		// Detect when the Login Designer panel is expanded (or closed) so we can preview the login form easily.
		wp.customize.panel( 'hfg_page_header', function( section ) {

			section.expanded.bind( function( isExpanding ) {

				// Value of isExpanding will = true if you're entering the section, false if you're leaving it.
				if ( isExpanding ) {

					// Only send the previewer to the login designer page, if we're not already on it.
					var current_url = wp.customize.previewer.previewUrl();
					var current_url = current_url.includes( pageHeader.blog );

					console.log( current_url )
					console.log( pageHeader )

					if ( ! current_url ) {
						wp.customize.previewer.send( 'page-header-open-designer', { expanded: isExpanding } );
					}

				} else {
					// Head back to the home page, if we leave the Login Designer panel.
					wp.customize.previewer.send( 'page-header-back-to-home', { home_url: wp.customize.settings.url.home } );
				}
			} );

		} );

	} );

} )( jQuery );