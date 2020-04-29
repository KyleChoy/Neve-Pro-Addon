<?php
/**
 * Module Class for Typekit fonts.
 *
 * Name:    Typekit fonts module.
 * Author:  Cristian Ungureanu <cristian@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Typekit_Fonts;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Typekit_Fonts
 */
class Module extends Abstract_Module {

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Typekit_Fonts';

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug              The slug of the module.
	 * @property string  $this->name              The pretty name of the module.
	 * @property string  $this->description       The description of the module.
	 * @property string  $this->documentation     Optional. Default array(). The documentation label and link.
	 * @property string  $this->order             Optional. Default 0. The order of display for the module.
	 * @property boolean $this->active            Optional. Default `false`. The state of the module by default.
	 * @property array   $this->settings_form     Optional. Default array(). Settings form to display in module box.
	 * @property array   $this->links             Optional. Default array(). Settings page label and link.
	 * @property array   $this->dependent_plugins Optional. Default array(). Dependent plugins for this module.
	 * @property string  $this->theme_min_version Optional. Default `2.3.10`. Dependent plugins for this module.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug        = 'typekit_fonts';
		$this->name        = __( 'Typekit Fonts', 'neve' );
		$this->description = __( 'Easily embed Adobe Fonts in your WordPress website.', 'neve' );

		/**
		 * TODO: Add documentation link
		 */
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1085-typekit-fonts-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);

		$this->theme_min_version = '2.3.13';

		$this->order               = 9;
		$query['autofocus[panel]'] = 'neve_typography';
		$typography_link           = add_query_arg( $query, admin_url( 'customize.php' ) );

		$this->settings_form = array(
			'neve_pro_typekit_id'     => array(
				'type'  => 'text',
				'label' => esc_html__( 'Project ID', 'neve' ),
			),
			'neve_pro_typekit_notice' => array(
				'type'    => 'notice_content_valid',
				'content' => sprintf(
					'%1$s %2$s',
					esc_html__( 'The fonts from your Typekit project are now available in customizer.', 'neve' ),
					sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						esc_url( $typography_link ),
						esc_html__( 'Go to customizer.', 'neve' )
					)
				),
			),
		);

		add_action( $this->slug . '_disable_actions', array( $this, 'reset_font' ) );
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load() {
		return $this->settings->is_module_active( $this->slug );
	}

	/**
	 * Run Header Footer Grid Module
	 */
	public function run_module() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_typekit_fonts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_typekit_fonts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_typekit_fonts' ) );
		add_filter( 'neve_react_controls_localization', array( $this, 'add_typekit_fonts' ) );
	}

	/**
	 * Enqueues a Typekit Font.
	 *
	 * @since 2.3.12
	 */
	public function enqueue_typekit_fonts() {
		$typekit_id = get_option( 'neve_pro_typekit_id' );
		if ( empty( $typekit_id ) ) {
			return;
		}

		$url = '//use.typekit.net/' . $typekit_id . '.css';

		wp_enqueue_style( 'neve-typekit-font', $url, array(), false );
	}

	/**
	 * List of Typekit fonts.
	 */
	public function add_typekit_fonts( $localization_data ) {
		$fonts         = array();
		$typekit_fonts = get_option( 'neve_pro_typekit_data' );
		if ( empty( $typekit_fonts ) ) {
			return $localization_data;
		}
		$typekit_fonts = json_decode( $typekit_fonts, true );
		foreach ( $typekit_fonts as $font_name => $font_options ) {
			$fonts[] = $font_options['family'];
		}
		$returnable = array_merge(
			[
				'System'  => [],
				'Typekit' => $fonts,
				'Google'  => [],
			],
			$localization_data['fonts']
		);

		$localization_data['fonts'] = $returnable;

		return $localization_data;
	}

	/**
	 * Reset fonts to default if module is disabled.
	 */
	public function reset_font() {
		$typekit_fonts = apply_filters( 'neve_typekit_fonts', array() );

		$headings_font = get_theme_mod( 'neve_headings_font_family', apply_filters( 'neve_headings_default', false ) );
		if ( ! empty( $headings_font ) && in_array( $headings_font, $typekit_fonts, true ) ) {
			set_theme_mod( 'neve_headings_font_family', 'default' );
		}

		$body_font = get_theme_mod( 'neve_body_font_family', apply_filters( 'neve_body_font_default', false ) );
		if ( ! empty( $body_font ) && in_array( $body_font, $typekit_fonts, true ) ) {
			set_theme_mod( 'neve_body_font_family', 'default' );
		}
	}
}
