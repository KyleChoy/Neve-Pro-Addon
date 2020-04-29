<?php
/**
 * Class that adds the metabox for Custom Layouts custom post type.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

use Neve_Pro\Admin\Conditional_Display;

/**
 * Class Layouts_Metabox
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class Layouts_Metabox {

	/**
	 * Custom layouts location.
	 *
	 * @var array
	 */
	private $layouts;

	/**
	 * Root rules.
	 *
	 * @var array
	 */
	private $root_ruleset;

	/**
	 * End rules.
	 *
	 * @var array
	 */
	private $end_ruleset;

	/**
	 * Ruleset map.
	 *
	 * @var array
	 */
	private $ruleset_map;

	/**
	 * Conditional display instance.
	 *
	 * @var Conditional_Display
	 */
	private $conditional_display = null;

	/**
	 * Conditional logic value.
	 *
	 * @var string
	 */
	private $conditional_logic_value;

	/**
	 * Availabele dynamic tags ma[.
	 *
	 * @var array
	 */
	public static $magic_tags = array(
		'archive_taxonomy' => array(
			'category' => array( '{title}', '{description}' ),
			'post_tag' => array( '{title}', '{description}' ),
		),
		'archive_type'     => array(
			'author' => array( '{author}', '{author_description}', '{author_avatar}' ),
			'date'   => array( '{date}' ),
		),
	);

	/**
	 * Layouts_Metabox constructor.
	 */
	public function __construct() {
		require_once( get_template_directory() . '/globals/utilities.php' );
	}

	/**
	 * Setup class properties.
	 */
	public function setup_props() {
		$this->conditional_display = new Conditional_Display();
		$this->layouts             = array(
			'header'    => __( 'Header', 'neve' ),
			'footer'    => __( 'Footer', 'neve' ),
			'hooks'     => __( 'Hooks', 'neve' ),
			'not_found' => __( '404 Page', 'neve' ),
		);

		if ( defined( 'PWA_VERSION' ) ) {
			$this->layouts['offline']      = __( 'Offline Page', 'neve' );
			$this->layouts['server_error'] = __( 'Internal Server Error Page', 'neve' );
		}

		$this->root_ruleset = $this->conditional_display->get_root_ruleset();
		$this->end_ruleset  = $this->conditional_display->get_end_ruleset();
		$this->ruleset_map  = $this->conditional_display->get_ruleset_map();
	}

	/**
	 * Initialize function.
	 */
	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'init', array( $this, 'setup_props' ), 999 );
		add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post_data' ) );
	}

	/**
	 * Create meta box.
	 */
	public function create_meta_box() {
		$post_type = get_post_type();
		if ( $post_type !== 'neve_custom_layouts' ) {
			return;
		}
		add_meta_box(
			'custom-layouts-settings',
			__( 'Custom Layout Settings', 'neve' ),
			array( $this, 'meta_box_markup' ),
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Save meta fields.
	 *
	 * @param int $post_id Post id.
	 */
	public function save_post_data( $post_id ) {
		$this->save_layout( $post_id, $_POST );
		$this->save_hook( $post_id, $_POST );
		$this->save_priority( $post_id, $_POST );
		$this->save_conditional_rules( $post_id, $_POST );
	}

	/**
	 * Save layout meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_layout( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-layout', $post ) ) {
			return false;
		}

		$choices = array( 'header', 'footer', 'hooks', 'not_found', 'offline', 'server_error' );
		if ( ! in_array( $post['nv-custom-layout'], $choices, true ) ) {
			return false;
		}
		update_post_meta(
			$post_id,
			'custom-layout-options-layout',
			$post['nv-custom-layout']
		);

		return true;
	}

	/**
	 * Save hook meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_hook( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-hook', $post ) ) {
			return false;
		}

		$hooks           = neve_hooks();
		$available_hooks = array();
		foreach ( $hooks as $list_of_hooks ) {
			$available_hooks = array_merge( $available_hooks, $list_of_hooks );
		}
		if ( ! in_array( $post['nv-custom-hook'], $available_hooks, true ) ) {
			return false;
		}

		update_post_meta(
			$post_id,
			'custom-layout-options-hook',
			$post['nv-custom-hook']
		);

		return true;
	}

	/**
	 * Save priority meta option.
	 *
	 * @param int   $post_id Post id.
	 * @param array $post    Post array.
	 *
	 * @return bool
	 */
	private function save_priority( $post_id, $post ) {
		if ( ! array_key_exists( 'nv-custom-priority', $post ) ) {
			return false;
		}
		update_post_meta(
			$post_id,
			'custom-layout-options-priority',
			(int) $post['nv-custom-priority']
		);

		return true;
	}

	/**
	 * Save the conditional rules.
	 *
	 * @param int   $post_id post ID.
	 * @param array $post    $_POST variables.
	 */
	private function save_conditional_rules( $post_id, $post ) {
		if ( empty( $post['custom-layout-conditional-logic'] ) ) {
			return;
		}
		update_post_meta(
			$post_id,
			'custom-layout-conditional-logic',
			$post['custom-layout-conditional-logic']
		);
	}

	/**
	 * Meta box HTML.
	 *
	 * @param \WP_Post $post Post.
	 */
	public function meta_box_markup( $post ) {
		$this->conditional_logic_value = $this->get_conditional_logic_value( $post );
		$is_header_layout              = get_post_meta( $post->ID, 'header-layout', true );
		$layout                        = get_post_meta( $post->ID, 'custom-layout-options-layout', true );
		echo '<table class="nv-custom-layouts-settings ' . ( $is_header_layout ? 'hidden' : '' ) . ' ">';
		echo '<tr>';
		echo '<td>';
		echo '<label>' . esc_html__( 'Layout', 'neve' ) . '</label>';
		echo '</td>';
		echo '<td>';
		echo '<select id="nv-custom-layout" name="nv-custom-layout">';
		echo '<option value="0">' . esc_html__( 'Select', 'neve' ) . '</option>';
		foreach ( $this->layouts as $layout_value => $layout_name ) {
			echo '<option ' . selected( $layout_value, $layout ) . ' value="' . esc_attr( $layout_value ) . '">' . esc_html( $layout_name ) . '</option>';
		}
		echo '</select>';
		echo '</td>';
		echo '</tr>';

		$hooks = neve_hooks();
		$hook  = get_post_meta( $post->ID, 'custom-layout-options-hook', true );
		$class = ( $layout !== 'hooks' ? 'hidden' : '' );
		if ( ! empty( $hooks ) ) {
			echo '<tr class="' . esc_attr( $class ) . '">';
			echo '<td>';
			echo '<label>' . esc_html__( 'Hooks', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<select id="nv-custom-hook" name="nv-custom-hook">';
			foreach ( $hooks as $hook_cat_slug => $hook_cat ) {
				echo '<optgroup label="' . esc_html( ucwords( $hook_cat_slug ) ) . '">';
				foreach ( $hook_cat as $hook_value ) {
					$hook_label = View_Hooks::beautify_hook( $hook_value );
					echo '<option ' . selected( $hook_value, $hook ) . ' value="' . esc_attr( $hook_value ) . '">' . esc_html( $hook_label ) . '</option>';
				}
				echo '</optgroup>';
			}
			echo '</select>';
			echo '</td>';
			echo '</tr>';

			$priority = get_post_meta( $post->ID, 'custom-layout-options-priority', true );
			if ( empty( $priority ) && $priority !== 0 ) {
				$priority = 10;
			}
			echo '<tr class="' . esc_attr( $class ) . '">';
			echo '<td>';
			echo '<label>' . esc_html__( 'Priority', 'neve' ) . '</label>';
			echo '</td>';
			echo '<td>';
			echo '<input value="' . esc_attr( $priority ) . '" type="number" id="nv-custom-priority" name="nv-custom-priority" min="1" max="150" step="1"/>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';

		$this->render_conditional_logic_setup( $post );
		$this->render_rule_group_template();
		?>
		<input type="hidden" class="nv-conditional-meta-collector" name="custom-layout-conditional-logic"
				id="custom-layout-conditional-logic" value="<?php echo esc_attr( $this->conditional_logic_value ); ?>"/>
		<?php
	}

	/**
	 * Get the conditional logic meta value.
	 *
	 * @param \WP_Post $post the post object.
	 *
	 * @return mixed|string
	 */
	private function get_conditional_logic_value( $post ) {
		$value = get_post_meta( $post->ID, 'custom-layout-conditional-logic', true );

		if ( empty( $value ) ) {
			$value = '{}';
		}

		return $value;
	}

	/**
	 * Render the conditional logic.
	 */
	private function render_conditional_logic_setup( $post ) {
		$value            = json_decode( $this->conditional_logic_value, true );
		$layout           = get_post_meta( $post->ID, 'custom-layout-options-layout', true );
		$class            = ( empty( $layout ) || in_array(
			$layout,
			[
				'not_found',
				'offline',
				'server_error',
			],
			true
		) ) ? 'hidden' : '';
		$is_header_layout = get_post_meta( $post->ID, 'header-layout', true );
		if ( $is_header_layout ) {
			$class = '';
		}
		?>
		<div id="nv-conditional" class="<?php echo esc_attr( $class ); ?>">
			<div>
				<label><?php echo esc_html__( 'Conditional Logic', 'neve' ); ?></label>
				<p class="<?php echo $is_header_layout ? 'hidden' : ''; ?>">
					<span class="dashicons dashicons-info"></span>
					<i>
						<?php echo esc_html__( 'If no conditional logic is selected, the Custom Layout will be applied site-wide.', 'neve' ); ?>
					</i>
				</p>
			</div>
			<div class="nv-rules-wrapper">
				<div class="nv-rule-groups">
					<?php
					if ( ! is_array( $value ) || empty( $value ) ) {
						$this->display_magic_tags();
						$this->render_rule_group();
					} else {
						$index = 0;
						foreach ( $value as $rule_group ) {
							$magic_tags = $this->get_magic_tags( $rule_group );
							$this->display_magic_tags( $magic_tags, $index );
							$this->render_rule_group( $rule_group );
							$index ++;
						}
					}
					?>
				</div>
				<div class="rule-group-actions">
					<button class="button button-primary nv-add-rule-group"><?php esc_html_e( 'Add Rule Group', 'neve' ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get magic tags based on current rules.
	 *
	 * @param array $rule_group Set of rules.
	 *
	 * @return array
	 */
	private function get_magic_tags( $rule_group ) {
		$all_magic_tags = array();
		foreach ( $rule_group as $rule ) {
			if ( $rule['condition'] !== '===' ) {
				return array();
			}

			if ( empty( $rule['root'] ) || empty( $rule['end'] ) ) {
				return array();
			}

			if ( ! array_key_exists( $rule['root'], self::$magic_tags ) ) {
				return array();
			}

			$end_array = self::$magic_tags[ $rule['root'] ];
			if ( ! array_key_exists( $rule['end'], $end_array ) ) {
				return array();
			}

			$all_magic_tags = array_merge( $all_magic_tags, $end_array[ $rule['end'] ] );
		}

		return $all_magic_tags;
	}

	/**
	 * Render rule group.
	 *
	 * @param array $rules The rules.
	 */
	private function render_rule_group( $rules = array() ) {
		if ( empty( $rules ) ) {
			$rules[] = array(
				'root'      => '',
				'condition' => '===',
				'end'       => '',
			);
		}
		?>
		<div class="nv-rule-group-wrap">
			<div class="nv-rule-group">
				<div class="nv-group-inner">
					<?php foreach ( $rules as $rule ) { ?>
						<div class="individual-rule">
							<div class="rule-wrap root_rule">
								<select class="nv-slim-select root-rule">
									<option value="" <?php echo $rule['root'] === '' ? 'selected' : ''; ?>><?php echo esc_html__( 'Select', 'neve' ); ?></option>
									<?php
									foreach ( $this->root_ruleset as $option_group_slug => $option_group ) {
										echo '<optgroup label="' . esc_attr( $option_group['label'] ) . '">';
										foreach ( $option_group['choices'] as $slug => $label ) {
											echo '<option value="' . esc_attr( $slug ) . '" ' . ( $slug === $rule['root'] ? 'selected' : '' ) . ' >' . esc_html( $label ) . '</option>';
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="rule-wrap condition">
								<select class="nv-slim-select condition-rule">
									<option value="==="
										<?php echo esc_attr( $rule['condition'] === '===' ? 'selected' : '' ); ?>>
										<?php echo esc_html__( 'is equal to', 'neve' ); ?></option>
									<option value="!=="
										<?php echo esc_attr( $rule['condition'] === '!==' ? 'selected' : '' ); ?>>
										<?php echo esc_html__( 'is not equal to', 'neve' ); ?></option>
								</select>
							</div>
							<div class="rule-wrap end_rule">
								<?php
								foreach ( $this->end_ruleset as $ruleset_slug => $options ) {
									$this->render_end_option( $ruleset_slug, $options, $rule['end'], $rule['root'] );
								}
								?>
							</div>
							<div class="actions-wrap">
								<button class="remove action button button-secondary">
									<i class="dashicons dashicons-no"></i>
								</button>
								<button class="duplicate action button button-primary">
									<i class="dashicons dashicons-plus"></i>
								</button>
							</div>
							<span class="operator and"><?php esc_html_e( 'AND', 'neve' ); ?></span>
						</div>
					<?php } ?>
				</div>
				<div class="rule-group-actions">
					<button class="button button-secondary nv-remove-rule-group"><?php esc_html_e( 'Remove Rule Group', 'neve' ); ?></button>
				</div>
			</div>
			<span class="operator or"><?php esc_html_e( 'OR', 'neve' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Display magic tags/
	 *
	 * @param array $magic_tags Array of magic tags.
	 *
	 * @return bool
	 */
	private function display_magic_tags( $magic_tags = '', $index = 0 ) {
		echo '<div class="nv-magic-tags" id="nv-magic-tags-group-' . $index . '">';
		if ( ! empty( $magic_tags ) ) {
			echo '<p>' . esc_html__( 'You can add the following tags in your template:', 'neve' ) . '</p>';
			echo '<ul class="nv-available-tags-list">';
			foreach ( $magic_tags as $magic_tag ) {
				echo '<li>' . $magic_tag . '</li>';
			}
			echo '</ul>';
		}
		echo '</div>';

		return true;
	}

	/**
	 * Render the end option.
	 *
	 * @param string $slug     the ruleset slug.
	 * @param array  $args     the ruleset options.
	 * @param string $end_val  the ruleset end value.
	 * @param string $root_val the ruleset root value.
	 */
	private function render_end_option( $slug, $args, $end_val, $root_val ) {
		?>
		<div class="single-end-rule <?php echo esc_attr( join( ' ', $this->ruleset_map[ $slug ] ) ); ?>">
			<select name="<?php echo esc_attr( $slug ); ?>"
					class="nv-slim-select end-rule">
				<option value="" <?php echo esc_attr( $end_val === '' ? 'selected' : '' ); ?>><?php echo esc_html__( 'Select', 'neve' ); ?></option>
				<?php
				switch ( $slug ) {
					case 'terms':
						foreach ( $args as $post_type_slug => $taxonomies ) {
							foreach ( $taxonomies as $taxonomy ) {
								if ( ! is_array( $taxonomy['terms'] ) || empty( $taxonomy['terms'] ) ) {
									continue;
								}
								echo '<optgroup label="' . $taxonomy['nicename'] . ' (' . $post_type_slug . ' - ' . $taxonomy['name'] . ')">';
								foreach ( $taxonomy['terms'] as $term ) {
									if ( ! $term instanceof \WP_Term ) {
										continue;
									}
									echo '<option value="' . esc_attr( $taxonomy['name'] ) . '|' . esc_attr( $term->slug ) . '" ' . esc_attr( ( $taxonomy['name'] ) . '|' . esc_attr( $term->slug ) === $end_val ? 'selected' : '' ) . '>' . esc_html( $term->name ) . '</option>';
								}
							}
							echo '</optgroup>';
						}
						break;
					case 'taxonomies':
						foreach ( $args as $post_type_slug => $taxonomies ) {
							foreach ( $taxonomies as $taxonomy ) {
								if ( ! is_array( $taxonomy['terms'] ) || empty( $taxonomy['terms'] ) ) {
									continue;
								}
								echo '<option value="' . esc_attr( $taxonomy['name'] ) . '" ' . esc_attr( (string) $taxonomy['name'] === $end_val ? 'selected' : '' ) . '>' . $taxonomy['nicename'] . ' (' . $post_type_slug . ' - ' . $taxonomy['name'] . ')' . '</option>';
							}
						}
						break;
					default:
						foreach ( $args as $value => $label ) {
							echo '<option value="' . esc_attr( $value ) . '" ' . esc_attr( (string) $value === $end_val ? 'selected' : '' ) . '>' . esc_html( $label ) . '</option>';
						}
						break;
				}
				?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render the rule group template.
	 */
	private function render_rule_group_template() {
		?>
		<div class="nv-rule-group-template">
			<?php $this->render_rule_group(); ?>
		</div>
		<?php
	}
}
