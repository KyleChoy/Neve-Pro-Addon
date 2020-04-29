<?php
/**
 * WPML and Polylang compatibility class
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Translations;

use Neve_Pro\Core\Settings;

/**
 * Class Translations_Manager
 *
 * @package Neve_Pro\Views
 */
class Translations_Manager {

	/**
	 * Check if WPML plugin exist and is enabled
	 *
	 * @return bool
	 */
	private function is_wpml_enabled() {
		return defined( 'WPML_PLUGIN_PATH' );
	}

	/**
	 * Check if Polylang plugin exist and is enabled
	 *
	 * @return bool
	 */
	private function is_pll_enabled() {
		return defined( 'POLYLANG_VERSION' );
	}

	/**
	 * Check if the Elementor plugin is enabled
	 *
	 * @return bool
	 */
	private function is_elementor_enabled() {
		return defined( 'ELEMENTOR_VERSION' );
	}

	/**
	 * Decide if class should load.
	 *
	 * @return bool
	 */
	private function should_load() {
		return $this->is_pll_enabled() || $this->is_wpml_enabled();
	}

	/**
	 * Init class functions.
	 *
	 * @return bool|void
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return false;
		}

		$settings = new Settings();
		if ( $this->is_wpml_enabled() && $this->is_elementor_enabled() && $settings->is_module_active( 'elementor_booster' ) ) {
			add_action( 'init', array( $this, 'add_wpml_elementor_widget_support' ) );
		}

		$this->manage_repeater_strings();
		add_filter( 'neve_translate_single_string', array( $this, 'translate_single_string' ), 10, 2 );
	}

	/**
	 * Run WPML Elementor custom widgets translation
	 */
	public function add_wpml_elementor_widget_support() {
		if ( ! class_exists( '\WPML_Elementor_Module_With_Items' ) ) {
			return false;
		}
		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'wpml_widgets_to_translate_filter' ) );
	}

	/**
	 * Register Elementor widgets fields
	 *
	 * @param array $widgets Elementor widgets.
	 *
	 * @return array
	 */
	public function wpml_widgets_to_translate_filter( $widgets ) {

		$widgets['neve_flipcard'] = array(
			'conditions'        => array( 'widgetType' => 'neve_flipcard' ),
			'fields'            => array(
				array(
					'field'       => 'frontside_content',
					'type'        => __( 'Flip card: front side content', 'neve' ),
					'editor_type' => 'VISUAL',
				),
				array(
					'field'       => 'backside_content',
					'type'        => __( 'Flip card: back side content', 'neve' ),
					'editor_type' => 'VISUAL',
				),
			),
			'integration-class' => 'Neve_Pro\Translations\Flip_Card_Wpml_Translate',
		);

		$widgets['neve_review_box'] = array(
			'conditions'        => array( 'widgetType' => 'neve_review_box' ),
			'fields'            => array(
				array(
					'field'       => 'title',
					'type'        => __( 'Review box: title', 'neve' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'price',
					'type'        => __( 'Review box: price', 'neve' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'pros_title',
					'type'        => __( 'Review box: pro features title', 'neve' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'cons_title',
					'type'        => __( 'Review box: cons features title', 'neve' ),
					'editor_type' => 'LINE',
				),
			),
			'integration-class' => array(
				'Neve_Pro\Translations\Review_Box_Wpml_Pros_Fields',
				'Neve_Pro\Translations\Review_Box_Wpml_Cons_Fields',
				'Neve_Pro\Translations\Review_Box_Wpml_Scores_Fields',
			),
		);

		$widgets['neve_typed_headline'] = array(
			'conditions' => array( 'widgetType' => 'neve_typed_headline' ),
			'fields'     => array(
				array(
					'field'       => 'before_text',
					'type'        => __( 'Typed Headline: Before typed text', 'neve' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'typed_text',
					'type'        => __( 'Typed Headline: Typed text', 'neve' ),
					'editor_type' => 'AREA',
				),
				array(
					'field'       => 'after_text',
					'type'        => __( 'Typed Headline: After typed text', 'neve' ),
					'editor_type' => 'LINE',
				),
			),
		);

		return $widgets;
	}

	/**
	 * Register all repeaters stings wrapper.
	 *
	 * @return void;
	 */
	private function manage_repeater_strings() {
		$repeaters_array = array(
			'social_icons_content_setting' => array(
				array(
					'title'            => 'Facebook',
					'url'              => '#',
					'icon'             => 'facebook',
					'visibility'       => 'yes',
					'icon_color'       => '#fff',
					'background_color' => '#3b5998',
				),
				array(
					'title'            => 'Twitter',
					'url'              => '#',
					'icon'             => 'twitter',
					'visibility'       => 'yes',
					'icon_color'       => '#fff',
					'background_color' => '#1da1f2',
				),
				array(
					'title'            => 'Youtube',
					'url'              => '#',
					'icon'             => 'youtube-play',
					'visibility'       => 'yes',
					'icon_color'       => '#fff',
					'background_color' => '#cd201f',
				),
				array(
					'title'            => 'Instagram',
					'url'              => '#',
					'icon'             => 'instagram',
					'visibility'       => 'yes',
					'icon_color'       => '#fff',
					'background_color' => '#e1306c',
				),
			),
			'contact_content_setting'      => array(
				array(
					'title'      => 'email@example.com',
					'icon'       => 'envelope',
					'item_type'  => 'email',
					'visibility' => 'yes',
				),
				array(
					'title'      => '202-555-0191',
					'icon'       => 'phone',
					'item_type'  => 'phone',
					'visibility' => 'yes',
				),
				array(
					'title'      => '499 Pirate Island Plaza',
					'icon'       => 'map-marker',
					'item_type'  => 'text',
					'visibility' => 'yes',
				),
			),
		);
		foreach ( $repeaters_array as $repeater_id => $repeater_default ) {
			$this->register_repeater_strings( $repeater_id, json_encode( $repeater_default ) );
		}
	}

	/**
	 * Register repeater strings.
	 *
	 * @param string $repeater_id Repeater id.
	 * @param array  $repeater_default Repeater default value.
	 *
	 * @return bool|void
	 */
	private function register_repeater_strings( $repeater_id, $repeater_default ) {
		$repeater_value = get_theme_mod( $repeater_id, $repeater_default );
		if ( empty( $repeater_default ) ) {
			return false;
		}
		$repeater_value = json_decode( $repeater_value, true );
		$index          = 1;
		foreach ( $repeater_value as $repeater_prop => $prop_value ) {
			if ( ! is_array( $prop_value ) ) {
				continue;
			}
			/**
			 * $context
			 * (string) (Required) This value gives the string you are about a context. This will usually be the name of the plugin or theme, in a human readable format
			 *
			 * $name
			 * (string) (Required) The name of the string which helps the translator understand what’s being translated
			 *
			 * $value
			 * (string) (Required) The string that needs to be translated
			 */
			foreach ( $prop_value as $item_name => $item_value ) {
				if ( ! is_string( $item_value ) ) {
					continue;
				}
				$name = $repeater_id . '_' . $item_name . '_' . $index;
				do_action( 'wpml_register_single_string', 'admin_texts_theme_mods_neve', $name, $item_value );
			}
			$index ++;
		}
	}

	/**
	 * Filter to translate strings
	 *
	 * @param string $original_value original string value.
	 *
	 * @return string
	 */
	public function translate_single_string( $original_value, $name = '' ) {

		if ( function_exists( 'pll__' ) ) {
			return pll__( $original_value );
		}

		if ( is_customize_preview() ) {
			return $original_value;
		}

		return apply_filters( 'wpml_translate_single_string', $original_value, 'admin_texts_theme_mods_neve', $name );
	}

}
