/* global elementorModules, Typed */
(function ( $, window ) {

	$( window ).on(
		'elementor/frontend/init', function () {

			var TypedHeadlineHandler = elementorModules.frontend.handlers.Base.extend(
				{

					onInit: function () {
						elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );

						var settings = this.getElementSettings();
						if ( typeof settings === 'undefined' ) {
							return;
						}

						var typed_text = settings.typed_text.split( '\n' ),
							speed = settings.speed,
							typed = this.$element.find( '.eaw-typed-text-placeholder' );

						if ( typed.length > 0 ) {
							new Typed(
								typed[ 0 ], {
									strings: typed_text,
									typeSpeed: speed.size || 30,
									loop: true
								}
							);
						}
					}
				}
			);

			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/neve_typed_headline.default', function ( $scope ) {
					new TypedHeadlineHandler( { $element: $scope } );
				}
			);

			var last_view = null;
			var flipcardCheck;
			if ( typeof elementor !== 'undefined') {
				window.elementor.hooks.addAction(
					'panel/open_editor/widget', function ( panel, model, view ) {
						console.log( view );
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
							}, 300
						);
					}
				);
			}
		}
	);

})( jQuery, window );
