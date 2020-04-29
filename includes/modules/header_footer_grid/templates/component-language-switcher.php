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

$plugins = array(
	'wpml'           => defined( 'ICL_SITEPRESS_VERSION' ),
	'translatepress' => defined( 'TRP_PLUGIN_VERSION' ),
	'polylang'       => defined( 'POLYLANG_VERSION' ),
);
$plugin  = null;
foreach ( $plugins as $key => $status ) {
	if ( $status !== true ) {
		continue;
	}
	$plugin = $key;
	break;
}

?>
<div class="component-wrap">
	<?php
	if ( $plugin === 'polylang' && function_exists( 'pll_the_languages' ) ) {
		echo '<ul class="nv--lang-switcher nv--pll">';
		pll_the_languages(
			array(
				'show_flags' => 1,
				'show_names' => 1,
				'dropdown'   => 0,
			)
		);
		echo '</ul>';
	}

	if ( $plugin === 'translatepress' ) {
		echo '<div class="nv--lang-switcher nv--tlp">';
		echo preg_replace( '#<script(.*?)>(.*?)</script>#is', '', do_shortcode( '[language-switcher]' ) );
		echo '</div>';
	}

	if ( $plugin === 'wpml' ) {
		echo '<div class="nv--lang-switcher nv--wpml">';
		do_action(
			'wpml_language_switcher',
			array(
				'flags'      => 1,
				'native'     => 0,
				'translated' => 0,
			)
		);
		echo '</div>';
	}
	?>
</div>
