( function( $ ) {

	// Switch to the /login-designer/ page, where we can live-preview Customizer options for Login Designer.
	wp.customize.bind( 'preview-ready', function() {

		wp.customize.preview.bind( 'page-header-open-designer', function( data ) {
			// When the section is expanded, open the login designer page specified via localization.
			if ( true === data.expanded ) {
				wp.customize.preview.send( 'url', pageHeader.blog );
			}
		});

		wp.customize.preview.bind( 'page-header-back-to-home', function( data ) {
			// Go back to home, if the section is closed.
			wp.customize.preview.send( 'url', data.home_url );
		});
	});

} )( jQuery );