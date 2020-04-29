<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Templates;

use Neve_Pro\Modules\Header_Footer_Grid\Components\Contact as Contact;
use Neve_Pro\Modules\Header_Footer_Grid\Components\Icons;

$content       = json_decode( \HFG\component_setting( Contact::REPEATER_ID ), true );
$icon_position = \HFG\component_setting( Contact::ICON_POSITION, 'left' );
?>
<div class="component-wrap">
	<ul class="nv-contact-list <?php echo esc_attr( $icon_position ); ?>">
		<?php
		$index = 1;
		foreach ( $content as $item ) {
			$item['title'] = apply_filters( 'neve_translate_single_string', $item['title'], 'contact_content_setting_title_' . $index );
			$index ++;

			if ( (string) $item['visibility'] === 'no' ) {
				continue;
			}
			$icon = Icons::get_instance()->get_single_icon( $item['icon'], 16 );
			?>
			<li>
				<?php
				if ( $icon_position === 'left' ) {
					echo '<span class="icon">';
					echo neve_kses_svg( $icon ); // WPCS: XSS OK.
					echo '</span>';
				}

				if ( $item['item_type'] === 'text' ) {
					echo '<span>' . esc_html( $item['title'] ) . '</span>';
				}
				if ( $item['item_type'] === 'email' ) {
					echo '<a href="mailto:' . esc_attr( $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
				}
				if ( $item['item_type'] === 'phone' ) {
					echo '<a href="tel:' . esc_attr( $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
				}

				if ( $icon_position === 'right' ) {
					?>
					<span class="icon">
					<?php echo neve_kses_svg( $icon ); // WPCS: XSS OK. ?>
					</span>
				<?php } ?>
			</li>
			<?php
		}
		?>
	</ul>
</div>
