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

use Neve_Pro\Modules\Header_Footer_Grid\Components\Icons;
use Neve_Pro\Modules\Header_Footer_Grid\Components\Social_Icons as Social_Icons;
use function HFG\component_setting;

$social_icons = json_decode( \HFG\component_setting( Social_Icons::REPEATER_ID ), true );
$icon_size    = \HFG\component_setting( Social_Icons::ICON_SIZE );
$new_tab      = (bool) \HFG\component_setting( Social_Icons::NEW_TAB );
$target       = $new_tab ? ' target="_blank" ' : '';
?>
<div class="component-wrap">
	<ul class="nv-social-icons-list">
		<?php
		$index = 1;
		foreach ( $social_icons as $social_icon ) {
			$social_icon['title'] = apply_filters( 'neve_translate_single_string', $social_icon['title'], 'social_icons_content_setting_title' . $index );
			$social_icon['url']   = apply_filters( 'neve_translate_single_string', $social_icon['url'], 'social_icons_content_setting_url' . $index );
			$index ++;

			if ( (string) $social_icon['visibility'] === 'no' ) {
				continue;
			}
			$icon_style = '';
			$icon       = Icons::get_instance()->get_single_icon( $social_icon['icon'], $icon_size );

			$icon_style .= empty( $social_icon['icon_color'] ) ? '' : 'fill:' . $social_icon['icon_color'] . ';';
			$icon_style .= empty( $social_icon['background_color'] ) ? '' : 'background-color:' . $social_icon['background_color'] . ';';
			?>
			<li>
				<a href="<?php echo esc_url( $social_icon['url'] ); ?>"
					<?php echo esc_attr( $target ); ?> style="<?php echo esc_attr( $icon_style ); ?>" title="<?php echo esc_html( $social_icon['title'] ); ?>" aria-label="<?php echo esc_attr( $social_icon['title'] ); ?>">
					<?php echo neve_kses_svg( $icon ); // WPCS: XSS OK. ?>
				</a>
			</li>
			<?php
		}
		?>
	</ul>
</div>
