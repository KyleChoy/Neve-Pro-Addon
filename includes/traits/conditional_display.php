<?php
/**
 * Conditional display trait
 *
 * Name:    Neve Pro Addon
 *
 * @package Neve_Pro
 */

namespace Neve_Pro\Traits;

/**
 * Trait Conditional_Display
 *
 * @package Neve_Pro\Traits
 */
trait Conditional_Display {

	/**
	 * The static rules array.
	 *
	 * @var array
	 */
	private $static_rules = [];

	/**
	 * Priority Map for root ruleset.
	 *
	 * @var array
	 */
	private $priority_map = [
		'post_type'        => 30,
		'post_taxonomy'    => 20,
		'post_author'      => 10,
		'post'             => 0,
		'page_type'        => 20,
		'page_template'    => 10,
		'page'             => 0,
		'archive_type'     => 30,
		'archive_taxonomy' => 20,
		'archive_term'     => 10,
		'archive_author'   => 0,
		'user_status'      => 20,
		'user_role'        => 10,
		'user'             => 0,
	];

	/**
	 * Check which rule has the highest priority.
	 *
	 * @param array $available_layouts available layouts array [ index => post_id ].
	 * @param bool  $is_header_layout  is this a header layout we are testing.
	 *
	 * @return int
	 */
	public function get_greatest_priority_rule( $available_layouts, $is_header_layout = false ) {
		if ( sizeof( $available_layouts ) === 1 ) {
			return $available_layouts[0];
		}

		$valid_layouts = [];

		foreach ( $available_layouts as $layout_index => $layout_id ) {
			$rules = json_decode( get_post_meta( $layout_id, 'custom-layout-conditional-logic', true ), true );
			if ( empty( $rules ) && ! $is_header_layout ) {
				return $layout_id;
			}
			foreach ( $rules as $index => $group ) {
				$group_state = true;
				$min_group   = 100;
				foreach ( $group as $individual_rule ) {
					if ( ! $this->evaluate_condition( $individual_rule ) ) {
						$group_state = false;
						break;
					}
					if ( $this->priority_map[ $individual_rule['root'] ] < $min_group ) {
						$min_group = $this->priority_map[ $individual_rule['root'] ];
					}
				}
				if ( $group_state === true ) {
					$valid_layouts[ $layout_id ] = isset( $valid_layouts[ $layout_id ] ) ? ( $min_group < $valid_layouts[ $layout_id ] ? $min_group : $valid_layouts[ $layout_id ] ) : $min_group;
				}
			}
		}

		if ( empty( $valid_layouts ) ) {
			return false;
		}

		return array_search( min( $valid_layouts ), $valid_layouts, true );
	}

	/**
	 * Check the display conditions.
	 *
	 * @param int $custom_layout_id the custom layout post ID.
	 *
	 * @return bool
	 */
	public function check_conditions( $custom_layout_id ) {
		$this->setup_static_rules();
		$condition_groups = json_decode( get_post_meta( $custom_layout_id, 'custom-layout-conditional-logic', true ), true );

		return $this->check_conditions_groups( $condition_groups );
	}

	/**
	 * Check conditions groups array.
	 *
	 * @param array $condition_groups the condition groups to check.
	 *
	 * @return bool
	 */
	public function check_conditions_groups( $condition_groups ) {
		if ( ! is_array( $condition_groups ) || empty( $condition_groups ) ) {
			return true;
		}
		$evaluated_groups = array();
		foreach ( $condition_groups as $index => $conditions ) {
			$individual_rules = array();
			foreach ( $conditions as $condition ) {
				$individual_rules[ $index ][] = $this->evaluate_condition( $condition );
			}
			$evaluated_groups[ $index ] = ! in_array( false, $individual_rules[ $index ], true );
		}

		return in_array( true, $evaluated_groups, true );
	}

	/**
	 * Setup static rules.
	 */
	private function setup_static_rules() {
		$this->static_rules = array(
			'page_type'    => array(
				'front_page' => get_option( 'show_on_front' ) === 'page' && is_front_page(),
				'not_found'  => is_404(),
				'posts_page' => is_home(),
			),
			'user_status'  => array(
				'logged_in'  => is_user_logged_in(),
				'logged_out' => ! is_user_logged_in(),
			),
			'archive_type' => array(
				'date'   => is_date(),
				'author' => is_author(),
				'search' => is_search(),
			),
		);

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			if ( $post_type === 'post' ) {
				$this->static_rules['archive_type'][ $post_type ] = is_home();
				continue;
			}
			$this->static_rules['archive_type'][ $post_type ] = is_post_type_archive( $post_type );
		}
	}

	/**
	 * Evaluate single condition
	 *
	 * @param array $condition condition.
	 *
	 * @return bool
	 */
	private function evaluate_condition( $condition ) {
		$post_id = null;
		global $post;
		if ( isset( $post->ID ) ) {
			$post_id = (string) $post->ID;
		}
		if ( ! is_array( $condition ) || empty( $condition ) ) {
			return true;
		}
		$evaluated = false;
		switch ( $condition['root'] ) {
			case 'post_type':
				$evaluated = is_singular( $condition['end'] );
				break;
			case 'post':
				$evaluated = is_single() && $post_id === $condition['end'];
				break;
			case 'page':
				$evaluated = is_page() && $post_id === $condition['end'];
				break;
			case 'page_template':
				$evaluated = get_page_template_slug() === $condition['end'];
				break;
			case 'page_type':
				$evaluated = $this->static_rules['page_type'][ $condition['end'] ];
				break;
			case 'post_taxonomy':
				$parts = preg_split( '/\|/', $condition['end'] );
				if ( is_array( $parts ) && sizeof( $parts ) === 2 ) {
					$evaluated = is_singular() && has_term( $parts[1], $parts[0], get_the_ID() );
				}
				break;
			case 'archive_term':
				$parts  = preg_split( '/\|/', $condition['end'] );
				$object = get_queried_object();
				if ( is_array( $parts ) && sizeof( $parts ) === 2 && $object instanceof \WP_Term && isset( $object->slug ) ) {
					$evaluated = $object->slug === $parts[1] && $object->taxonomy === $parts[0];
				}
				break;
			case 'archive_taxonomy':
				$object = get_queried_object();
				if ( $object instanceof \WP_Term && isset( $object->taxonomy ) && isset( $object->slug ) ) {
					$evaluated = $object->taxonomy === $condition['end'];
				}
				break;
			case 'archive_type':
				if ( isset( $this->static_rules['archive_type'][ $condition['end'] ] ) ) {
					$evaluated = $this->static_rules['archive_type'][ $condition['end'] ];
				}
				break;
			case 'user':
				$evaluated = (string) get_current_user_id() === $condition['end'];
				break;
			case 'post_author':
				$evaluated = is_singular() && (string) $post->post_author === $condition['end'];
				break;
			case 'archive_author':
				$evaluated = is_author( $condition['end'] );
				break;
			case 'user_status':
				$evaluated = $this->static_rules['user_status'][ $condition['end'] ];
				break;
			case 'user_role':
				$user      = wp_get_current_user();
				$evaluated = in_array( $condition['end'], $user->roles, true );
				break;
		}
		if ( $condition['condition'] === '===' ) {
			return $evaluated;
		}

		return ! $evaluated;
	}
}
