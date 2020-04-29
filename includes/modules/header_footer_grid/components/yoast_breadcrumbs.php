<?php
/**
 * Button Component class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;

/**
 * Class Yoast_Breadcrumbs
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Yoast_Breadcrumbs extends Abstract_Component {
	const COMPONENT_ID     = 'yoast_breadcrumbs';
	const HTML_TAG         = 'html_tag';
	const LINK_COLOR       = 'link_color';
	const LINK_HOVER_COLOR = 'link_hover_color';

	/**
	 * Allowed html tags for breadcrumb.
	 *
	 * @var array
	 */
	private $allowed_html_tags = array(
		'div'   => 'div',
		'span'  => 'span',
		'small' => 'small',
		'p'     => 'p',
		'h1'    => 'h1',
		'h2'    => 'h2',
		'h3'    => 'h3',
		'h4'    => 'h4',
		'h5'    => 'h5',
		'h6'    => 'h6',
	);

	/**
	 * Breadcrumbs constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Breadcrumbs', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 4 );
		$this->set_property( 'section', 'yoast_breadcrumbs' );
		$this->set_property( 'icon', 'editor-ul' );
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::HTML_TAG,
				'group'              => self::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . self::COMPONENT_ID,
				'sanitize_callback'  => array( $this, 'sanitize_breadcrumb_tag' ),
				'label'              => __( 'HTML Tag', 'neve' ),
				'default'            => 'small',
				'type'               => 'select',
				'options'            => array(
					'choices' => $this->allowed_html_tags,
				),
				'section'            => $this->section,
				'conditional_header' => true,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::LINK_COLOR,
				'group'              => self::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . self::COMPONENT_ID,
				'sanitize_callback'  => 'sanitize_hex_color',
				'label'              => __( 'Links Color', 'neve' ),
				'type'               => 'neve_color_control',
				'section'            => $this->section,
				'conditional_header' => true,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::LINK_HOVER_COLOR,
				'group'              => self::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . self::COMPONENT_ID,
				'sanitize_callback'  => 'sanitize_hex_color',
				'label'              => __( 'Links Hover Color', 'neve' ),
				'type'               => 'neve_color_control',
				'section'            => $this->section,
				'conditional_header' => true,
			)
		);
	}

	/**
	 * Sanitize the breadcrumb
	 *
	 * @param string $value the option value.
	 *
	 * @return string
	 */
	public function sanitize_breadcrumb_tag( $value ) {
		if ( ! array_key_exists( $value, $this->allowed_html_tags ) ) {
			return 'small';
		}

		return $value;
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_style( array $css_array = array() ) {
		$this->default_selector = '.builder-item--' . $this->get_id() . ' .nv--yoast-breadcrumb';

		$color       = \HFG\component_setting( self::LINK_COLOR, null, $this->id );
		$hover_color = \HFG\component_setting( self::LINK_HOVER_COLOR, null, $this->id );

		if ( ! empty( $color ) ) {
			$css_array['.builder-item--yoast_breadcrumbs .nv--yoast-breadcrumb a'] = array( 'color' => sanitize_hex_color( $color ) );
		}

		if ( ! empty( $hover_color ) ) {
			$css_array['.builder-item--yoast_breadcrumbs .nv--yoast-breadcrumb a:hover'] = array( 'color' => sanitize_hex_color( $hover_color ) );
		}

		return parent::add_style( $css_array );
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		add_filter( 'neve_show_breadcrumbs', '__return_false' );
		Main::get_instance()->load( 'component-yoast-breadcrumbs' );
	}

	/**
	 * Check if component should be active.
	 *
	 * @return bool
	 */
	public function is_active() {
		if ( ! function_exists( 'yoast_breadcrumb' ) ) {
			return false;
		}

		return true;
	}
}
