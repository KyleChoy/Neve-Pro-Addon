var ContentSwitcher = function ($scope, $) {

	var contentSwitcher = $scope.find( ".neb-content-switcher-container" );

	var radioSwitch = contentSwitcher.find( ".neb-content-switcher-switch" ),
		contentList = contentSwitcher.find( ".neb-content-switcher-two-content" );

	radioSwitch.prop( 'checked', false );

	var sides = {};
	sides[0]  = contentList.find( 'li[data-type="neb-content-switcher-front"]' );
	sides[1]  = contentList.find( 'li[data-type="neb-content-switcher-back"]' );

	radioSwitch.on(
		"click",
		function (event) {
			var selected_filter = $( event.target ).val();

			if ( $( this ).hasClass( "neb-content-switcher-switch-active" ) ) {
				selected_filter = 0;
				$( this ).toggleClass( "neb-content-switcher-switch-normal neb-content-switcher-switch-active" );
				hide_not_selected_items( sides, selected_filter );
			} else if ( $( this ).hasClass( "neb-content-switcher-switch-normal" ) ) {
				selected_filter = 1;
				$( this ).toggleClass( "neb-content-switcher-switch-normal neb-content-switcher-switch-active" );
				hide_not_selected_items( sides, selected_filter );
			}
		}
	);

	function hide_not_selected_items(sides, filter) {
		$.each(
			sides,
			function (key, value) {
				if ( key != filter ) {
					$( this ).removeClass( "neb-content-switcher-is-visible" ).addClass( "neb-content-switcher-is-hidden" );
				} else {
					$( this ).addClass( "neb-content-switcher-is-visible" ).removeClass( "neb-content-switcher-is-hidden" );
				}
			}
		);
	}
};

jQuery( window ).on(
	'elementor/frontend/init',
	function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/neve_content_switcher.default', ContentSwitcher );
	}
);
