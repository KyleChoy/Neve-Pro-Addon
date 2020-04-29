<?php
/**
 * Template used for page header rendering.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */

namespace HFG;

use Neve_Pro\Modules\Header_Footer_Grid\Builder\Page_Header as PageHeaderBuilder;

$classes = apply_filters( 'hfg_page_header_wrapper_class', '' );
?>
<div id="page-header-grid"  class="<?php echo esc_attr( get_builder( PageHeaderBuilder::BUILDER_NAME )->get_property( 'panel' ) ) . esc_attr( $classes ); ?> page-header">
	<?php
	// var_dump( PageHeaderBuilder::BUILDER_NAME );
	render_builder( PageHeaderBuilder::BUILDER_NAME );
	?>
</div>
