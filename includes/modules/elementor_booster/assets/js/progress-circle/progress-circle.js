( function( $ ) {
	var ProgressCircle = function( $scope, $ ) {

		var $circle = $scope.find( '.neb-progress-circle' );
		$circle.appear(
			function() {

				$( $circle ).asPieProgress(
					{
						namespace       : 'pieProgress',
						classes         : {
							svg     : 'neb-progress-circle-svg',
							number  : 'neb-progress-circle-number',
							content : 'neb-progress-circle-content'
						}
					}
				);

				$( $circle ).asPieProgress( 'start' );

			}
		);

	};
	$( window ).on(
		'elementor/frontend/init',
		function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/neve_progress_circle.default', ProgressCircle );
		}
	);
} )( jQuery );
