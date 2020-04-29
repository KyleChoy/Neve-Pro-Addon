<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-02-11
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use Neve\Views\Base_View;

/**
 * Class Single_Product_Video
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Single_Product_Video extends Base_View {


	/**
	 * Initialize the module.
	 */
	public function init() {
		add_filter(
			'woocommerce_single_product_image_thumbnail_html',
			array(
				$this,
				'replace_featured_image',
			),
			10,
			2
		);

		add_filter( 'woocommerce_placeholder_img_src', array( $this, 'add_video_thumbnail_to_placeholder' ) );
		add_filter( 'woocommerce_single_product_image_gallery_classes', array( $this, 'add_gallery_classes' ) );
	}

	/**
	 * Register submodule hooks
	 */
	public function register_hooks() {
		$this->init();
	}

	/**
	 * Add video thumbnail as placeholder when we have a video but not a product image.
	 *
	 * @param string $src Thumbnail URL.
	 *
	 * @return string
	 */
	public function add_video_thumbnail_to_placeholder( $src ) {
		if ( ! is_product() ) {
			return $src;
		}
		$video_url = $this->get_video_url();
		if ( empty( $video_url ) ) {
			return $src;
		}

		return $this->get_video_thumbnail( $video_url );
	}

	/**
	 * Add classes to the gallery.
	 *
	 * @param array $classes classes for the gallery.
	 *
	 * @return array
	 */
	public function add_gallery_classes( $classes ) {
		if ( ! is_product() ) {
			return $classes;
		}
		$vid_url = $this->get_video_url();
		if ( empty( $vid_url ) ) {
			return $classes;
		}
		$classes[] = 'nv-video-product';
		if ( strpos( $vid_url, 'vimeo' ) || strpos( $vid_url, 'youtube' ) || strpos( $vid_url, 'youtu.be' ) ) {
			return $classes;
		}
		$classes[] = 'nv-video-file-src';

		return $classes;
	}

	/**
	 * Replaces featured image with video
	 *
	 * @param int $html         The html.
	 * @param int $thumbnail_id The thumbnail id.
	 *
	 * @return string
	 */
	public function replace_featured_image( $html, $thumbnail_id ) {

		if ( ! is_product() ) {
			return $html;
		}
		$video_url = $this->get_video_url();

		if ( empty( $video_url ) ) {
			return $html;
		}
		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

		if ( $thumbnail_id !== $post_thumbnail_id ) {
			return $html;
		}

		if ( empty( $thumbnail_id ) ) {
			$html = '';
		}

		$video = '<div data-thumb="' . $this->get_video_gallery_thumbnail() . '" class="woocommerce-product-gallery__image nv-featured-video">';

		$video .= '<div class="nv-video-wrap" id="nv-featured-vid">';
		$video .= $this->get_iframe( $video_url );
		$video .= '</div>';

		$video .= '</div>';

		$html = $video . $html;

		return $html;
	}

	/**
	 * Get the video iframe.
	 *
	 * @param string $url the video url.
	 *
	 * @return string
	 */
	private function get_iframe( $url ) {
		if ( empty( $url ) ) {
			return '';
		}

		if ( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
			$parts = parse_url( $url );
			parse_str( $parts['query'], $query );
			$video_id = $query['v'];

			return '<iframe src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '?enablejsapi=1"  
			allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen" frameborder="0"></iframe>';
		}

		if ( strpos( $url, 'vimeo' ) ) {
			$id = substr( $url, strrpos( $url, '/' ) + 1 );

			return '<iframe src="https://player.vimeo.com/video/' . esc_attr( $id ) . '?loop=1&title=0&byline=0&portrait=0" frameborder="0"></iframe>';
		}

		return '<video height="350" controls><source src="' . esc_url( $url ) . '" type="video/mp4"></video>';
	}

	/**
	 * Gets the standard gallery image.
	 *
	 * @return string
	 */
	private function get_video_gallery_thumbnail() {
		$b64 = 'data:image/jpeg;base64,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';

		return apply_filters( 'neve_pro_product_video_gallery_thumb_src', $b64 );
	}

	/**
	 * Get thumbnail for the video url.
	 *
	 * @param string $url the video url.
	 *
	 * @return string
	 */
	private function get_video_thumbnail( $url ) {
		if ( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
			return $this->get_youtube_thumbnail( $url );
		}
		if ( strpos( $url, 'vimeo' ) ) {
			return $this->get_vimeo_thumbnail( $url );
		}
	}

	/**
	 * Get YouTube thumbnail.
	 *
	 * @param string $url the video url.
	 *
	 * @return string
	 */
	private function get_youtube_thumbnail( $url ) {
		$parts = parse_url( $url );
		parse_str( $parts['query'], $query );
		$video_id = $query['v'];

		return esc_url( 'https://img.youtube.com/vi/' . $video_id . '/sddefault.jpg' );
	}

	/**
	 * Get vimeo thumbnail.
	 *
	 * @param string $url the video url.
	 *
	 * @return string
	 */
	private function get_vimeo_thumbnail( $url ) {
		$id  = substr( $url, strrpos( $url, '/' ) + 1 );
		$api = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$id.php" ) );

		return esc_url( $api[0]['thumbnail_small'] );
	}

	/**
	 * Get the video URL from the meta field.
	 *
	 * @return string
	 */
	private function get_video_url() {
		return get_post_meta( get_the_ID(), 'neve_meta_product_video_link', true );
	}
}
