<?php
/**
 * Course page controls LifterLMS Module
 *
 * @package LifterLMS Booster
 */

namespace Neve_Pro\Modules\LifterLMS_Booster\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Section;

/**
 * Class Course_Membership
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Customizer
 */
class Course_Membership extends Base_Customizer {

	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->add_course_membership_section();
		$this->add_course_layout_controls();
		$this->add_membership_layout_controls();
		$this->add_colors();
	}

	/**
	 * Add Course/Membership section.
	 */
	private function add_course_membership_section() {
		$this->add_section(
			new Section(
				'neve_liftellms',
				array(
					'priority' => 85,
					'title'    => esc_html__( 'LifterLMS', 'neve' ),
					'panel'    => 'neve_layout',
				)
			)
		);
	}

	/**
	 * Add course customizer controls.
	 */
	private function add_course_layout_controls() {

		$this->add_control(
			new Control(
				'neve_course_page_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Courses Shop', 'neve' ),
					'section'          => 'neve_liftellms',
					'priority'         => 10,
					'class'            => 'course-page-layout',
					'accordion'        => true,
					'controls_to_wrap' => 4,
					'expanded'         => true,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_courses_per_row',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => json_encode(
						array(
							'desktop' => 3,
							'tablet'  => 2,
							'mobile'  => 1,
						)
					),
				),
				array(
					'label'      => esc_html__( 'Courses per row', 'neve' ),
					'section'    => 'neve_liftellms',
					'units'      => array(
						'items',
					),
					'input_attr' => array(
						'mobile'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 1,
						),
						'tablet'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 2,
						),
						'desktop' => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 3,
						),
					),
					'priority'   => 15,
					'responsive' => true,
				),
				'Neve\Customizer\Controls\Responsive_Number'
			)
		);

		$this->add_control(
			new Control(
				'neve_course_pagination_type',
				array(
					'default'           => 'number',
					'sanitize_callback' => array( $this, 'sanitize_pagination_type' ),
				),
				array(
					'label'    => esc_html__( 'Pagination', 'neve' ),
					'section'  => 'neve_liftellms',
					'priority' => 20,
					'type'     => 'select',
					'choices'  => array(
						'number'   => esc_html__( 'Number', 'neve' ),
						'infinite' => esc_html__( 'Infinite Scroll', 'neve' ),
					),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_course_box_shadow_intensity',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 0,
				),
				array(
					'label'      => esc_html__( 'Card Box shadow (px)', 'neve' ),
					'section'    => 'neve_liftellms',
					'type'       => 'range-value',
					'step'       => 1,
					'input_attr' => array(
						'min'     => 0,
						'max'     => 30,
						'default' => 0,
					),
					'priority'   => 30,
				),
				'Neve\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_course_card_layout',
				array(
					'default'           => 'grid',
					'sanitize_callback' => array( $this, 'sanitize_card_layout' ),
				),
				array(
					'label'    => esc_html__( 'Card Layout', 'neve' ),
					'section'  => 'neve_liftellms',
					'priority' => 35,
					'choices'  => array(
						'grid' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEtElEQVR42u3cIXPjOhSG4f3/9KOGgoFmhmZlspvUabITsp5LjMyMzgWSHadNE7fJve1G74fSmbTgGUk+OpL7y8hn8gsCvPDCCy+8CF544YUXXngRvPDCCy+8yP/g9c/vbwteeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeP0Ur9dbsk/Ma9/U1U2pX1Py2j9XN6dJyOsOXFW1TcZrew+uqk7Ga3MXrwsD7MG87sN1YQV7SK96+9VqYpOk1/rL5egrXnjhhddP8drjtdxrv6mrqm72eC3y2sWd9/MeryVeU6Nig9cCr9leco/Xda/N5f3NS1Xv8Jp5rS96barqDFjKXi+X+g1h8L0DY/06v36Nc/UtWNLPx6nX+vJxm+wNWNJeYy9/c6mreAqWeH3fPFfVenu5CXsCxn77es96DobXghb/DAyvJSciRzC8Fh0gTWB47ep6e/28bQRL3mtXz8v7j48n6z1exxbY9vpp7itex45hALt4+I3XjKuqttfuCuA156qq7ZWrFXj9bj5zZQIvvPDCCy+8EvHarj+RHV7cz8ELL7zwwguv+fsK66+mTsyr5v2OT3nd6f2hXSpeu7twrdN5/7G5A1e9S+h95OY/5XrA9913m5sW/efL1/Ufz4v/p4AXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXkl6dX++LX+l1yMGL7zwwgsvvAheeOGFF1543T9OMfnh+pe9lOM1przdq1zyVx7FSx6vJV65mVmTSSu8FnuZlzSY937ocpVmvZck34evNSvJNee9vJNUHhLzaiT1JqmRVFo7TtLRQZIyKTeT1JpZL6k36920/MVvKaHxFVBU9mE585K6YHkIau+8nFSYHTKpSckrrl+SVA5mRZxYpVSYufgsyN97NZIzM3uSigSfj4p8WZyIraShD6NsXO9PvArpKdF6ojQzSc1xrsUP7TjHmvdeLvxCel6hvj9dm/D6eL0PiRan83E4Ox87Sb3lcT723vuEvWbrfW62iuv9avTyUwHi43rv01nvz3l17+oJP9UTK8n11jlJvQ2xngi/WP6IDfk3eNnhXL0axlcTfghewW18XPh06tU3Xmf3Q733jZkdVpL8MD4Twn6onareh/Z6xOCFF1544YUXwQsvvPDC6+b4HC+8bkyzmvUhfCa5cC+gfZo3KPCKyWP7qrXj2avrx1bW2ADDK+ZpOh7qzVbjZ3fsF8YmF15mFg55ijCYnsa2cyHpUEiu83nvfkRH/sd4jccVhVRMhxyZ5J3kzefmpQyvKWU8DiulwlxcrJzkV9GL5+NJDt63I5F57/s4RzsvqcgdXh8NMzfEz8NKKqYHQeYHvN6ky0MJEUbceGLUxYoia/F6u+ariKNoKOb3WL3zkrIer1lFsZLcWJO2mZR38/3Q4BbcA07Iq8uOg2sswCzcBxjM52Y5XrMM2awebXW8nJTFeqLP8Dq7HZI7boekMtYTrF+nyWZeneZ3DfPpM8/HY2ZErj3xsiY0K8q/r6FDvxCvh/T6S4MXXnjhhRdeBC+88MILL7wIXnjhhdcj51987R/KzzMKWAAAAABJRU5ErkJggg==',
						),
						'list' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAE0UlEQVR42u3csVPqTBTG4fv/t2+bckvKdCnT2W2iGASH5ma+JlW6VOcrdkOCIKBBwJvfW+GMOvLM7ubsctY/Rr6SPxDghRdeeOFF8MILL7zwwovghRdeeOFF8MILL7zwwovghRdeeOGFF8ELL7zwwovghRdeeOGFF8ELL7zwwovg9RNe/73fLXjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjh9Sheb1OymZnXpiqLSSnf5uS1eS4mp5qR1xW4imL1bS+nmHR7/n16Kb2z1+oaXEU53UvKp3vll/yWSV7Lq3idGGCXe8n/Aq/rcJ1Ywc57pWZmVSItfo1XufpuNbG8kpd5SZ1577smVW7Weknybfi2aiG56riXd5Ly7S29Xr5djr5dy6uS1JqkSlJudT9JewdJSqTUTFJtZq2k1qx1u+Uvfpdm4RXGV0BR3oblzEtqguU2qB14OSkz2yZSNSevuH5JUt6ZZXFi5VJm5uKzID30qiRnZvYkZTOZj+PnoyJfEidiLalrwyjr1/s9r0x6uv16P/La3M0rNzNJ1TDX4ou6n2PVoZcLP3Anr82yLIqy2tzBK9T3+2vTo3ut4877eXNLr3GJEC3252N3dD42klpL43xsvfc399odVCzv7TVa71OzRVzvF72X3xUgPq73/g7r/WgvubmzV3NQT/hdPbGQXGuNk9RaF+uJ8IP5ZRvyK3ktT+9vXotyfSsv2x6rV8P4qsIXwSu49Y8Lf9P66+Wk17IojoD9lNfR/VDrfWVm24Uk3/XPhLAfqndV7828Xk+dN4TBdwA21esuucH61c/Vj2Bz9hrOWl8/4zoAm7VXf5a//JzrI9isvd431XNRvKxOcX0Am7fX8Xw8sx6D4XWWaw8Mr/NcYzC8LuAageG1LsvVOa4BbPZe63Jc3n/+8WS5wet9dwS2OsdVFG94DSeGAezkh994jbiKYnWuVwCvMVdRrM60VuD1Xn2lZQIvvPDCCy+8ZuK1evlC1nh9I3jhhddMvHyKF14TvarFqE/CJ5IL9xbqp3EDxWN5FS/fTTnVK43tNbUNveGu7Vtt+gadh/GaeJdv8v2Op137amu26F+7oZ8pNuE8iteV7g+tv+nVSsrCYHrq2+IySdtMco1PW3dZx+DNvNZX4Tqx/p1Z0WM7ZSZluybMRPJO8uZT81LySF5f2zOe+XDt6155bNfNpcxcXKyc5BfR69Gej9cAO8V1xmvrfd0Tmfe+jXO08ZKy1D2g1/t6OWnRfz7drn/RG8gl18XX3ULKdg+CxHeP5vWzueDPb9JQQoQR13e0NrGiSGq8Pq75yuIo6rLxPVvvvKSkxWtUUSwkt41f1ImUNoNkap274J7yjLyaZBhcfQFm4b5CZz41S/EapUtG9Wit4fJUEuuJNsFrlGE7JDdsh6Q81hOsX/tJRl6Nxnch091rno9DRkSu3vOyKhxW5D9woPOv/j+rh9wP4fXPeP1U8MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILrwlezd+75Vd6EbzwwgsvvPAieOGFF154EbzwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwmpz/AdpnH8pqwZaBAAAAAElFTkSuQmCC',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);
	}

	/**
	 * Add membership customizer controls.
	 */
	private function add_membership_layout_controls() {

		$this->add_control(
			new Control(
				'neve_membership_page_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Memberships Shop', 'neve' ),
					'section'          => 'neve_liftellms',
					'priority'         => 100,
					'class'            => 'membership-page-layout',
					'accordion'        => true,
					'controls_to_wrap' => 4,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_memberships_per_row',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => json_encode(
						array(
							'desktop' => 3,
							'tablet'  => 2,
							'mobile'  => 1,
						)
					),
				),
				array(
					'label'      => esc_html__( 'Memberships per row', 'neve' ),
					'section'    => 'neve_liftellms',
					'units'      => array(
						'items',
					),
					'input_attr' => array(
						'mobile'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 1,
						),
						'tablet'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 2,
						),
						'desktop' => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 3,
						),
					),
					'priority'   => 110,
					'responsive' => true,
				),
				'Neve\Customizer\Controls\Responsive_Number'
			)
		);

		$this->add_control(
			new Control(
				'neve_membership_pagination_type',
				array(
					'default'           => 'number',
					'sanitize_callback' => array( $this, 'sanitize_pagination_type' ),
				),
				array(
					'label'    => esc_html__( 'Pagination', 'neve' ),
					'section'  => 'neve_liftellms',
					'priority' => 115,
					'type'     => 'select',
					'choices'  => array(
						'number'   => esc_html__( 'Number', 'neve' ),
						'infinite' => esc_html__( 'Infinite Scroll', 'neve' ),
					),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_membership_box_shadow_intensity',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 0,
				),
				array(
					'label'      => esc_html__( 'Card Box shadow (px)', 'neve' ),
					'section'    => 'neve_liftellms',
					'type'       => 'range-value',
					'step'       => 1,
					'input_attr' => array(
						'min'     => 0,
						'max'     => 30,
						'default' => 0,
					),
					'priority'   => 125,
				),
				'Neve\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_membership_card_layout',
				array(
					'default'           => 'grid',
					'sanitize_callback' => array( $this, 'sanitize_card_layout' ),
				),
				array(
					'label'    => esc_html__( 'Card Layout', 'neve' ),
					'section'  => 'neve_liftellms',
					'priority' => 130,
					'choices'  => array(
						'grid' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEtElEQVR42u3cIXPjOhSG4f3/9KOGgoFmhmZlspvUabITsp5LjMyMzgWSHadNE7fJve1G74fSmbTgGUk+OpL7y8hn8gsCvPDCCy+8CF544YUXXngRvPDCCy+8yP/g9c/vbwteeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeP0Ur9dbsk/Ma9/U1U2pX1Py2j9XN6dJyOsOXFW1TcZrew+uqk7Ga3MXrwsD7MG87sN1YQV7SK96+9VqYpOk1/rL5egrXnjhhddP8drjtdxrv6mrqm72eC3y2sWd9/MeryVeU6Nig9cCr9leco/Xda/N5f3NS1Xv8Jp5rS96barqDFjKXi+X+g1h8L0DY/06v36Nc/UtWNLPx6nX+vJxm+wNWNJeYy9/c6mreAqWeH3fPFfVenu5CXsCxn77es96DobXghb/DAyvJSciRzC8Fh0gTWB47ep6e/28bQRL3mtXz8v7j48n6z1exxbY9vpp7itex45hALt4+I3XjKuqttfuCuA156qq7ZWrFXj9bj5zZQIvvPDCCy+8EvHarj+RHV7cz8ELL7zwwguv+fsK66+mTsyr5v2OT3nd6f2hXSpeu7twrdN5/7G5A1e9S+h95OY/5XrA9913m5sW/efL1/Ufz4v/p4AXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXkl6dX++LX+l1yMGL7zwwgsvvAheeOGFF1543T9OMfnh+pe9lOM1przdq1zyVx7FSx6vJV65mVmTSSu8FnuZlzSY937ocpVmvZck34evNSvJNee9vJNUHhLzaiT1JqmRVFo7TtLRQZIyKTeT1JpZL6k36920/MVvKaHxFVBU9mE585K6YHkIau+8nFSYHTKpSckrrl+SVA5mRZxYpVSYufgsyN97NZIzM3uSigSfj4p8WZyIraShD6NsXO9PvArpKdF6ojQzSc1xrsUP7TjHmvdeLvxCel6hvj9dm/D6eL0PiRan83E4Ox87Sb3lcT723vuEvWbrfW62iuv9avTyUwHi43rv01nvz3l17+oJP9UTK8n11jlJvQ2xngi/WP6IDfk3eNnhXL0axlcTfghewW18XPh06tU3Xmf3Q733jZkdVpL8MD4Twn6onareh/Z6xOCFF1544YUXwQsvvPDC6+b4HC+8bkyzmvUhfCa5cC+gfZo3KPCKyWP7qrXj2avrx1bW2ADDK+ZpOh7qzVbjZ3fsF8YmF15mFg55ijCYnsa2cyHpUEiu83nvfkRH/sd4jccVhVRMhxyZ5J3kzefmpQyvKWU8DiulwlxcrJzkV9GL5+NJDt63I5F57/s4RzsvqcgdXh8NMzfEz8NKKqYHQeYHvN6ky0MJEUbceGLUxYoia/F6u+ariKNoKOb3WL3zkrIer1lFsZLcWJO2mZR38/3Q4BbcA07Iq8uOg2sswCzcBxjM52Y5XrMM2awebXW8nJTFeqLP8Dq7HZI7boekMtYTrF+nyWZeneZ3DfPpM8/HY2ZErj3xsiY0K8q/r6FDvxCvh/T6S4MXXnjhhRdeBC+88MILL7wIXnjhhdcj51987R/KzzMKWAAAAABJRU5ErkJggg==',
						),
						'list' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAE0UlEQVR42u3csVPqTBTG4fv/t2+bckvKdCnT2W2iGASH5ma+JlW6VOcrdkOCIKBBwJvfW+GMOvLM7ubsctY/Rr6SPxDghRdeeOFF8MILL7zwwovghRdeeOFF8MILL7zwwovghRdeeOGFF8ELL7zwwovghRdeeOGFF8ELL7zwwovg9RNe/73fLXjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjh9Sheb1OymZnXpiqLSSnf5uS1eS4mp5qR1xW4imL1bS+nmHR7/n16Kb2z1+oaXEU53UvKp3vll/yWSV7Lq3idGGCXe8n/Aq/rcJ1Ywc57pWZmVSItfo1XufpuNbG8kpd5SZ1577smVW7Weknybfi2aiG56riXd5Ly7S29Xr5djr5dy6uS1JqkSlJudT9JewdJSqTUTFJtZq2k1qx1u+Uvfpdm4RXGV0BR3oblzEtqguU2qB14OSkz2yZSNSevuH5JUt6ZZXFi5VJm5uKzID30qiRnZvYkZTOZj+PnoyJfEidiLalrwyjr1/s9r0x6uv16P/La3M0rNzNJ1TDX4ou6n2PVoZcLP3Anr82yLIqy2tzBK9T3+2vTo3ut4877eXNLr3GJEC3252N3dD42klpL43xsvfc399odVCzv7TVa71OzRVzvF72X3xUgPq73/g7r/WgvubmzV3NQT/hdPbGQXGuNk9RaF+uJ8IP5ZRvyK3ktT+9vXotyfSsv2x6rV8P4qsIXwSu49Y8Lf9P66+Wk17IojoD9lNfR/VDrfWVm24Uk3/XPhLAfqndV7828Xk+dN4TBdwA21esuucH61c/Vj2Bz9hrOWl8/4zoAm7VXf5a//JzrI9isvd431XNRvKxOcX0Am7fX8Xw8sx6D4XWWaw8Mr/NcYzC8LuAageG1LsvVOa4BbPZe63Jc3n/+8WS5wet9dwS2OsdVFG94DSeGAezkh994jbiKYnWuVwCvMVdRrM60VuD1Xn2lZQIvvPDCCy+8ZuK1evlC1nh9I3jhhddMvHyKF14TvarFqE/CJ5IL9xbqp3EDxWN5FS/fTTnVK43tNbUNveGu7Vtt+gadh/GaeJdv8v2Op137amu26F+7oZ8pNuE8iteV7g+tv+nVSsrCYHrq2+IySdtMco1PW3dZx+DNvNZX4Tqx/p1Z0WM7ZSZluybMRPJO8uZT81LySF5f2zOe+XDt6155bNfNpcxcXKyc5BfR69Gej9cAO8V1xmvrfd0Tmfe+jXO08ZKy1D2g1/t6OWnRfz7drn/RG8gl18XX3ULKdg+CxHeP5vWzueDPb9JQQoQR13e0NrGiSGq8Pq75yuIo6rLxPVvvvKSkxWtUUSwkt41f1ImUNoNkap274J7yjLyaZBhcfQFm4b5CZz41S/EapUtG9Wit4fJUEuuJNsFrlGE7JDdsh6Q81hOsX/tJRl6Nxnch091rno9DRkSu3vOyKhxW5D9woPOv/j+rh9wP4fXPeP1U8MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILrwlezd+75Vd6EbzwwgsvvPAieOGFF154EbzwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwmpz/AdpnH8pqwZaBAAAAAElFTkSuQmCC',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);
	}

	/**
	 * Add colors for LifterLMS.
	 */
	private function add_colors() {

		$this->add_control(
			new Control(
				'neve_lifter_colors_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Colors', 'neve' ),
					'section'          => 'neve_liftellms',
					'priority'         => 200,
					'class'            => 'lifter-colors',
					'accordion'        => true,
					'controls_to_wrap' => 1,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_lifter_primary_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => '#2295ff',
				),
				array(
					'label'    => 'Primary Color',
					'section'  => 'neve_liftellms',
					'priority' => 205,
				),
				'WP_Customize_Color_Control'
			)
		);

	}

	/**
	 * Sanitize the pagination type
	 *
	 * @param string $value value from the control.
	 *
	 * @return bool
	 */
	public function sanitize_pagination_type( $value ) {
		$allowed_values = array( 'number', 'infinite' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize card layout value.
	 *
	 * @param string $value Value from the control.
	 *
	 * @return bool
	 */
	public function sanitize_card_layout( $value ) {
		$allowed_values = array( 'list', 'grid' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'grid';
		}

		return esc_html( $value );
	}
}
