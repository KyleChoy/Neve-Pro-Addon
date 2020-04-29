var last_view = null;
var flipcardCheck;
var FlipCard = function ( panel, model, view ) {

	// Check if the clicked widget is not 'neve_flipcard' and remove the interval, resetting the last flipped card.
	if ( model.attributes.widgetType !== 'neve_flipcard' ) {
		clearInterval( flipcardCheck );
		if ( last_view !== null ) {
			last_view.$el.find( '.eaw-flipcard' ).removeClass( 'eaw_flipped' );
		}
		last_view = null;
		return;
	}
	// Save the last view if it was previously null.
	if ( last_view === null ) {
		last_view = view;
	}
	// Check if the last view is different from the current view.
	if ( last_view.cid !== view.cid ) {
		last_view.$el.find( '.eaw-flipcard' ).removeClass( 'eaw_flipped' );
		last_view = view;
		clearInterval( flipcardCheck );
	}
	// Use setInterval to continuously check if the backside is being edited.
	flipcardCheck = setInterval(
		function () {
			if ( panel.$el.find( '.elementor-control-eaw_backside_section, .elementor-control-eaw_backside_style' ).hasClass( 'elementor-open' ) ) {
				last_view.$el.find( '.eaw-flipcard' ).addClass( 'eaw_flipped' );
			} else {
				last_view.$el.find( '.eaw-flipcard' ).removeClass( 'eaw_flipped' );
			}
		},
		300
	);
};

jQuery( window ).on(
	'elementor/frontend/init',
	function () {
		elementor.hooks.addAction( 'panel/open_editor/widget', FlipCard );
	}
);
