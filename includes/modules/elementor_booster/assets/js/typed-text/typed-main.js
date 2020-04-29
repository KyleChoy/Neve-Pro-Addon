jQuery( window ).on(
	'elementor/frontend/init',
	function () {

		var TypedHeadlineHandler = elementorModules.frontend.handlers.Base.extend(
			{
				onInit: function () {
					elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
					this.run();
				},
				run: function () {
					var settings = this.getElementSettings();
					if ( typeof settings === 'undefined' ) {
						return;
					}

					var typed_text = settings.typed_text.split( '\n' ),
						speed      = settings.speed,
						typed      = this.$element.find( '.eaw-typed-text-placeholder' );

					if ( typed.length > 0 ) {
						new Typed(
							typed[ 0 ],
							{
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
			'frontend/element_ready/neve_typed_headline.default',
			function ( $scope ) {
				new TypedHeadlineHandler( { $element: $scope } );
			}
		);
	}
);
