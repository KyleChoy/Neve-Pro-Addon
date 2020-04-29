<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-04-23
 *
 * @package Neve_Pro\Customizer\Controls
 */

namespace Neve_Pro\Customizer\Controls;

use Neve_Pro\Modules\Header_Footer_Grid\Components\Icons;
use Neve_Pro\Traits\Core;

/**
 * Class Repeater
 *
 * @package Neve_Pro\Customizer\Controls
 */
class Repeater extends \WP_Customize_Control {
	use Core;

	/**
	 * Repeater fields.
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * Type of control.
	 *
	 * @var string
	 */
	public $type = 'neve-repeater';

	/**
	 * Default value.
	 *
	 * @var string
	 */
	public $default;

	/**
	 * Value of setting.
	 *
	 * @var string
	 */
	private $value;

	/**
	 * Repeater constructor.
	 *
	 * @param \WP_Customize_Manager $manager customize manager instance.
	 * @param string                $id      control id.
	 * @param array                 $args    arguments array.
	 */
	public function __construct( \WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );
		$value       = json_decode( $this->value(), true );
		$this->value = $this->rec_wp_parse_args( $value, json_decode( $this->default, true ) );
	}

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		echo '<div class="nv-repeater--wrap" data-tmpl="' . esc_attr( $this->id ) . '-tmpl">';
		$this->render_header();
		echo '<div class="nv-repeater--items-wrap">';
		foreach ( $this->value as $item_index => $item_values ) {
			$this->render_item( $item_values, $item_index );
		}
		echo '</div>';
		$this->render_footer();
		echo '</div>';
	}

	/**
	 * Render single repeater item.
	 *
	 * @param array  $item_values array of item values.
	 * @param string $item_index  item index.
	 */
	private function render_item( $item_values = array(), $item_index = '' ) {
		echo '<div class="nv-repeater--item">';
		$this->item_header( $item_values );
		echo '<div class="nv-repeater-item--content">';
		foreach ( $this->fields as $field_id => $args ) {
			$this->render_field( $args, $field_id, $item_index );
		}
		echo '<a href="#" class="nv-repeater--remove-item">' . esc_html__( 'Remove', 'neve' ) . '</a>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render repeater footer.
	 */
	private function render_footer() {
		?>
		<div class="nv-repeater--footer">
			<a href="#" class="nv-repeater--reorder">
				<span class="reorder"><?php echo esc_html__( 'Reorder', 'neve' ); ?></span>
				<span class="reorder-done"><?php echo esc_html__( 'Done', 'neve' ); ?></span>
			</a>
			<button type="button"
					class="button nv-repeater--add-new">
				<?php echo esc_html__( 'Add Item', 'neve' ); ?>
			</button>
		</div>
		<div class="nv-repeater--hidden-item">
			<?php $this->render_item(); ?>
		</div>
		<input type="hidden" class="nv-repeater--collector" <?php $this->link(); ?>
				value="<?php echo esc_attr( json_encode( $this->value ) ); ?>">
		<?php
	}

	/**
	 * Render repeater header.
	 */
	private function render_header() {

	}

	/**
	 * Render repeater item header.
	 *
	 * @param array $item_values Item values.
	 */
	private function item_header( $item_values ) {
		$title      = isset( $item_values['title'] ) ? $item_values['title'] : __( 'Item', 'neve' );
		$visibility = isset( $item_values['visibility'] ) ? $item_values['visibility'] : 'yes';
		$class      = $visibility === 'yes' ? '' : 'visibility-hidden';
		?>
		<div class="nv-repeater--header <?php echo esc_attr( $class ); ?>">
			<span class="nv-repeater--toggle has-value" data-key="visibility"
					data-value="<?php echo esc_attr( $visibility ); ?>">
				<i class="dashicons dashicons-visibility"></i>
				<i class="dashicons dashicons-hidden"></i>
			</span>
			<div class="nv-repeater--item-title">
				<span class="nv-repeater--title-text"
						data-default="<?php echo esc_html__( 'Item', 'neve' ); ?>"><?php echo esc_attr( $title ); ?></span>
				<span class="closed dashicons dashicons-arrow-down"></span>
				<span class="opened dashicons dashicons-arrow-up"></span>
				<div class="nv-repeater--reorder-buttons">
					<a class="reorder-btn up"><i class="dashicons dashicons-arrow-up"></i></a>
					<a class="reorder-btn down"><i class="dashicons dashicons-arrow-down"></i></a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render single repeater field.
	 *
	 * @param array  $args       the field args.
	 * @param string $id         the field ID.
	 * @param string $item_index the item index.
	 */
	private function render_field( $args, $id, $item_index = '' ) {
		$field_type = isset( $args['type'] ) ? $args['type'] : 'text';
		echo '<div class="nv-repeater--field">';
		if ( isset( $args['label'] ) && $args['type'] !== 'checkbox' ) {
			echo '<label>' . esc_html( $args['label'] ) . '</label>';
		}
		switch ( $field_type ) {
			case 'select':
				$this->render_select_field( $args, $id, $item_index );
				break;
			case 'color':
				$this->render_color_field( $args, $id, $item_index );
				break;
			case 'icon':
				$this->render_icon_field( $args, $id, $item_index );
				break;
			case 'checkbox':
				$this->render_checkbox_field( $args, $id, $item_index );
				break;
			case 'text':
			default:
				$this->render_text_field( $args, $id, $item_index );
				break;
		}
		echo '</div>';
	}

	/**
	 * Render text field.
	 *
	 * @param array  $args       Arguments for text field.
	 * @param string $id         The id for the select.
	 * @param string $item_index The item index.
	 */
	private function render_text_field( $args, $id, $item_index = '' ) {
		$field_value = isset( $this->value[ $item_index ][ $id ] ) ? $this->value[ $item_index ][ $id ] : '';
		echo '<input type="text" class="nv-repeater-text-field has-value" data-key="' . esc_attr( $id ) . '" value="' . esc_attr( $field_value ) . '"/>';
	}

	/**
	 * Render color field.
	 *
	 * @param array  $args       Arguments for color field.
	 * @param string $id         The id for the select.
	 * @param string $item_index The item index.
	 */
	private function render_color_field( $args, $id, $item_index = '' ) {
		$field_value = isset( $this->value[ $item_index ][ $id ] ) ? $this->value[ $item_index ][ $id ] : '';
		?>
		<div class="nv--color-picker">
			<input class="color-picker-hex has-value" type="text" maxlength="7"
					value="<?php echo esc_attr( $field_value ); ?>" data-key="<?php echo esc_attr( $id ); ?>"/>
		</div>
		<?php
	}

	/**
	 * Render icon field.
	 *
	 * @param array  $args       Arguments for the icon field.
	 * @param string $id         The id for the select.
	 * @param string $item_index The item index.
	 */
	private function render_icon_field( $args, $id, $item_index = '' ) {
		$field_value = isset( $this->value[ $item_index ][ $id ] ) ? $this->value[ $item_index ][ $id ] : '';
		$icon_value  = $field_value ? Icons::get_instance()->get_single_icon( $field_value, 20 ) : '<span class="dashicons dashicons-plus"></span>';
		$icons       = Icons::get_instance()->get_icons( 20 );
		?>
		<div class="nv--icon-field-wrap">
			<div class="form">
				<a href="#" class="nv--icon-selector button"><?php echo neve_kses_svg( $icon_value ); ?></a>
				<input type="text" class="nv-repeater-text-field has-value" data-key="<?php echo esc_attr( $id ); ?>"
						value="<?php echo esc_attr( $field_value ); ?>" disabled/>
				<a href="#" class="button nv--remove-icon"><i class="dashicons dashicons-no"></i></a>
			</div>
			<div class="nv--icons-container">
				<div class="nv--icons-search">
					<?php echo neve_kses_svg( Icons::get_instance()->get_single_icon( 'search', 15 ) ); // WPCS: XSS OK. ?>
					<input type="search" placeholder="<?php echo esc_html__( 'Find an icon...', 'neve' ); ?>"/>
				</div>
				<?php
				foreach ( $icons as $id => $icon ) {
					$class = $field_value === $id ? 'class="selected"' : '';
					?>
					<a href="#" title="<?php echo esc_attr( $id ); ?>"
							data-icon="<?php echo esc_attr( $id ); ?>" <?php echo wp_kses_post( $class ); ?>>
						<?php echo neve_kses_svg( $icon ); // WPCS: XSS OK. ?>
					</a>
					<?php
				}
				?>
			</div>
		</div>

		<?php
	}

	/**
	 * Render the select field.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param array  $args       Arguments for select.
	 * @param string $id         The id for the select.
	 * @param string $item_index The item index.
	 */
	private function render_select_field( $args, $id, $item_index = '' ) {
		$field_value = isset( $this->value[ $item_index ][ $id ] ) ? $this->value[ $item_index ][ $id ] : '';
		?>
		<div class="nv--select-field">
			<select class="has-value"
					value="<?php echo esc_attr( $field_value ); ?>" data-key="<?php echo esc_attr( $id ); ?>">
				<?php
				foreach ( $args['choices'] as $option_value => $option_label ) {
					?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php echo $field_value === $option_value ? ' selected ' : ''; ?>><?php echo esc_html( $option_label ); ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render the checkbox field.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param array  $args       Arguments for checkbox.
	 * @param string $id         The id for the checkbox.
	 * @param string $item_index The item index.
	 */
	private function render_checkbox_field( $args, $id, $item_index = '' ) {
		$field_value = isset( $this->value[ $item_index ][ $id ] ) ? $this->value[ $item_index ][ $id ] : true;
		?>
		<div class="nv--checkbox-field">
			<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
			<input type="checkbox" class="has-value"
				<?php checked( $field_value ); ?> data-key="<?php echo esc_attr( $id ); ?>"
					name="<?php echo esc_attr( $id ); ?>"/>
		</div>
		<?php
	}

}
