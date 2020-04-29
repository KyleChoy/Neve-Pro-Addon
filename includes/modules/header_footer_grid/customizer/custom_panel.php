<?php
/**
 * Customizer Class for Header Footer Grid.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use WP_Customize_Panel;

/**
 * Class Custom_Panel
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Customizer
 */
class Custom_Panel extends WP_Customize_Panel {
	/**
	 * Type of this panel.
	 *
	 * @since 4.1.0
	 * @var string
	 */
	public $type = 'neve-pro-panel';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 4.1.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array                          = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type' ) );
		$array['title']                 = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']               = $this->get_content();
		$array['active']                = $this->active();
		$array['instanceNumber']        = $this->instance_number;
		$array['autoExpandSoleSection'] = $this->auto_expand_sole_section;

		$array['panel_settings'] = apply_filters( $this->id . '_neve_panel_settings', '' );

		return $array;
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Panel::json().
	 *
	 * @see WP_Customize_Panel::print_template()
	 *
	 * @since 4.3.0
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button class="customize-panel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back' ); ?></span></button>
			<div class="accordion-section-title">
				<span class="preview-notice">
				<?php
				/* translators: %s: the site/panel title in the Customizer */
				echo sprintf( __( 'You are customizing %s' ), '<strong class="panel-title">{{ data.title }}</strong>' );
				?>
				</span>
				<# if ( data.description ) { #>
				<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help' ); ?></span></button>
				<# } #>
			</div>
			<# if ( data.description ) { #>
			<div class="description customize-panel-description">
				{{{ data.description }}}
			</div>
			<# } #>

			<# if ( data.panel_settings ) { #>
			<div class="description customize-panel-settings" style="background-color: #fff; padding: 8px; border-top: 1px solid #EDEDED;">
				{{{ data.panel_settings }}}
			</div>
			<# } #>

			<div class="customize-control-notifications-container"></div>
		</li>
		<?php
	}
}
