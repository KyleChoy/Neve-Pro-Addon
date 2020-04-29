<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */

namespace HFG;

use Neve_Pro\Modules\Header_Footer_Grid\Components\Html;

$content = component_setting( Html::CONTENT_ID );
$content = apply_filters( 'neve_translate_single_string', $content );
$content = apply_filters( 'neve_page_header_content', $content );
?>
<div class="nv-html-content">
	<?php echo wp_kses_post( balanceTags( parse_dynamic_tags( $content ), true ) ); ?>
</div>
