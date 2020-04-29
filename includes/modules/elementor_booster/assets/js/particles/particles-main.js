jQuery( window ).on(
	'elementor/frontend/init',
	function () {
		var Particles = elementorModules.frontend.handlers.Base.extend(
			{
				onInit: function () {
					elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
					this.run();
				},
				run: function () {
					var settings = this.getElementSettings();

					var isParticleEnabled = settings.neb_particle_switch === 'yes';
					if ( ! isParticleEnabled ) {
						return false;
					}

					var isDisabledOnReduced = settings.neb_reduced_motion_switch === 'yes';
					if ( window.matchMedia( '(prefers-reduced-motion)' ).matches && isDisabledOnReduced ) {
						return false;
					}

					var theme        = settings.neb_particle_preset_themes;
					var custom_style = settings.neb_particles_custom_style;
					var source       = settings.neb_particle_theme_from;
					var z_index		 = settings.neb_particle_area_zindex;

					if ( source === 'custom' && custom_style === '' ) {

						return false;
					}
					this.$element.addClass( 'neb-particles-section' );

					var sectionId = this.$element.data( 'id' );
					this.$element.attr( 'id', 'neb-section-particles-' + sectionId );

					var themes = typeof custom_style !== 'undefined' && custom_style !== '' ? JSON.parse( custom_style ) : JSON.parse( nebData.ParticleThemesData[theme] );
					particlesJS( 'neb-section-particles-' + sectionId, themes );

					jQuery( '#neb-section-particles-' + sectionId ).find( 'canvas' ).css( 'z-index', z_index );
				}
			}
		);

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/section',
			function ( $scope ) {
				new Particles( { $element: $scope } );
			}
		);
	}
);
