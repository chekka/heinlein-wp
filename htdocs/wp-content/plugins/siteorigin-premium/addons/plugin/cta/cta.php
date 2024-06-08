<?php
/*
Plugin Name: SiteOrigin Call-To-Action
Description: Boost your Call To Action Widget with expanded settings and styles, offering precise control to create compelling buttons that engage and convert your audience.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/call-to-action/
Tags: Widgets Bundle
Video: 314963197
Requires: so-widgets-bundle/cta
*/

class SiteOrigin_Premium_Plugin_Cta {
	public function __construct() {
		add_action( 'init', array( $this, 'init_addon' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Do any required intialization.
	 */
	public function init_addon() {
		$this->add_filters();
	}

	/**
	 * Add filters for modifying various widget related properties and configuration.
	 */
	public function add_filters() {
		if ( class_exists( 'SiteOrigin_Widget_Cta_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-cta', array( $this, 'admin_form_options' ), 10, 2 );

			add_filter( 'siteorigin_widgets_less_variables_sow-cta', array( $this, 'add_less_variables' ), 10, 3 );

			add_action( 'siteorigin_widgets_cta_after_wrapper', array( $this, 'add_html_background' ) );

			add_filter( 'siteorigin_widgets_less_vars_sow-cta', array( $this, 'add_less' ), 20, 3 );
		}
	}

	/**
	 * Filters the admin form for the call-to-action widget to add Premium fields.
	 *
	 * @param $form_options array The Call-To-Action Widget's form options.
	 * @param $widget SiteOrigin_Widget_Cta_Widget The widget object.
	 *
	 * @return mixed The updated form options array containing the new and modified fields.
	 */
	public function admin_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$form_options['design']['fields']['image'] = array(
			'type' => 'section',
			'label' => __( 'Image', 'siteorigin-premium' ),
			'fields' => array(
				'file' => array(
					'type' => 'media',
					'label' => __( 'Image', 'siteorigin-premium' ),
					'library' => 'image',
					'fallback' => true,
				),
				'display' => array(
					'type' => 'select',
					'label' => __( 'Image Display', 'siteorigin-premium' ),
					'default' => 'background',
					'options' => array(
						'background' => __( 'Background Image', 'siteorigin-premium' ),
						'right' => __( 'Right of Text', 'siteorigin-premium' ),
						'left' => __( 'Left of Text', 'siteorigin-premium' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args' => array( 'image' )
					),
				),
				'background_style' => array(
					'type' => 'select',
					'label' => __( 'Background Style', 'siteorigin-premium' ),
					'default' => 'cover',
					'options' => array(
						'cover' => __( 'Cover', 'siteorigin-premium' ),
						'contain' => __( 'Contain', 'siteorigin-premium' ),
						'fixed' => __( 'Fixed', 'siteorigin-premium' ),
						'parallax' => __( 'Parallax', 'siteorigin-premium' ),
					),
					'state_handler' => array(
						'image[background]' => array( 'show' ),
						'_else[image]' => array( 'hide' ),
					),
				),
				'gutter' => array(
					'label' => __( 'Gutter', 'siteorigin-premium' ),
					'type' => 'measurement',
					'default' => '25px',
					'state_handler' => array(
						'image[left,right]' => array( 'show' ),
						'_else[image]' => array( 'hide' ),
					),
				),
				'image_size' => array(
					'type' => 'image-size',
					'label' => __( 'Image Size', 'siteorigin-premium' ),
					'default' => 'full',
				),
			),
		);

		$form_options['design']['fields']['layout']['fields']['content_vertical_align'] = array(
			'name' => 'alignment',
			'type' => 'select',
			'label' => __( 'Content Vertical Alignment', 'siteorigin-premium' ),
			'default' => 'center',
			'state_handler' => array(
				'image[right,left]' => array( 'show' ),
				'_else[image]' => array( 'hide' ),
			),
			'options' => array(
				'start' => __( 'Top', 'siteorigin-premium' ),
				'center' => __( 'Center', 'siteorigin-premium' ),
				'end' => __( 'Bottom', 'siteorigin-premium' ),
			),
		);

		$form_options['design']['fields']['layout']['fields']['padding'] = array(
			'type' => 'multi-measurement',
			'label' => __( 'Padding', 'siteorigin-premium' ),
			'default' => '2em 2.5em 2em 2.5em',
			'measurements' => array(
				'top' => array(
					'label' => __( 'Top', 'siteorigin-premium' ),
					'units' => array( 'px', '%', 'em' ),
				),
				'right' => array(
					'label' => __( 'Right', 'siteorigin-premium' ),
					'units' => array( 'px', '%', 'em' ),
				),
				'bottom' => array(
					'label' => __( 'Bottom', 'siteorigin-premium' ),
					'units' => array( 'px', '%', 'em' ),
				),
				'left' => array(
					'label' => __( 'Left', 'siteorigin-premium' ),
					'units' => array( 'px', '%', 'em' ),
				),
			),
		);

		return $form_options;
	}

	/**
	 * Filters the new design related fields into the LESS variables used for the LESS stylesheet.
	 *
	 * @param $less_variables array LESS variable values to be used in the LESS stylesheet.
	 * @param $instance array The widget instance containing possible values to be used in the LESS stylesheet.
	 * @param $widget SiteOrigin_Widget_Cta_Widget The widget object.
	 *
	 * @return mixed The updated LESS variables containing the new and modified variables.
	 */
	public function add_less_variables( $less_variables, $instance, $widget ) {
		if ( empty( $less_variables ) ) {
			return $less_variables;
		}

		$less_variables['padding'] = ! empty( $instance['design']['layout']['padding'] ) ? $instance['design']['layout']['padding'] : '2em 2.5em';
		if ( ! empty( $instance['design']['image'] ) ) {
			if (
				(
					! empty( $instance['design']['image']['file'] ) ||
					! empty( $instance['design']['image']['file_fallback'] )
				) &&
				$instance['design']['image']['display'] == 'background' &&
				$instance['design']['image']['background_style'] != 'parallax'
			) {
				$less_variables['image'] = 'url( "' . esc_url( self::get_background( $instance ) ) . '" )';
			}

			$less_variables['background_style'] = ! empty( $instance['design']['image']['background_style'] ) ? $instance['design']['image']['background_style'] : 'cover';
			$less_variables['background_display'] = ! empty( $instance['design']['image']['display'] ) ? $instance['design']['image']['display'] : 'background';
			$less_variables['content_align'] = ! empty( $instance['design']['layout']['content_vertical_align'] ) ? $instance['design']['layout']['content_vertical_align'] : 'top';

			$less_variables['button_align'] = ! empty( $instance['design']['layout']['desktop'] ) ? $instance['design']['layout']['desktop'] : '';
			$less_variables['button_align_mobile'] = ! empty( $instance['design']['layout']['mobile'] ) ? $instance['design']['layout']['mobile'] : '';

			if (
				! empty( $instance['design']['image']['gutter'] ) &&
				(
					$instance['design']['image']['display'] == 'left' ||
					$instance['design']['image']['display'] == 'right'
				)
			) {
				$less_variables['gutter'] = $instance['design']['image']['gutter'];
			}
		}

		return $less_variables;
	}

	private static function get_background( $instance ) {
		$background = siteorigin_widgets_get_attachment_image_src(
			$instance['design']['image']['file'],
			$instance['design']['image']['image_size'],
			! empty( $instance['design']['image']['file_fallback'] ) ? $instance['design']['image']['file_fallback'] : false
		);

		if ( ! empty( $background ) ) {
			return $background[0];
		}

		return false;
	}

	public function add_html_background( $instance ) {
		if (
			! empty( $instance['design']['image'] ) &&
			(
				! empty( $instance['design']['image']['file'] ) ||
				! empty( $instance['design']['image']['file_fallback'] )
			)
		) {

			if (
				$instance['design']['image']['display'] == 'left' ||
				$instance['design']['image']['display'] == 'right'
			) {
				$image = siteorigin_widgets_get_attachment_image(
					$instance['design']['image']['file'],
					$instance['design']['image']['image_size'],
					! empty( $instance['design']['image']['file_fallback'] ) ? $instance['design']['image']['file_fallback'] : false,
					array( 'class' => 'siteorigin-premium-cta-image' )
				);

				if ( ! empty( $image ) ) {
					echo $image;
				}
			} elseif ( $instance['design']['image']['background_style'] == 'parallax' ) {
				wp_enqueue_script( 'siteorigin-parallax' );
				wp_enqueue_script( 'simpleParallax' );

				$background = self::get_background( $instance );
				if ( ! empty( $background ) ) {
					?>
					<img
						class="siteorigin-premium-cta-background-parallax"
						src="<?php echo esc_url( $background ); ?>"
						data-siteorigin-parallax="true"
						loading="eager"
						style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index: 0;"
					>
					<?php
				}
			}
		}
	}

	/**
	 * If an image is set, import a LESS file.
	 *
	 * @param string $less The current less content.
	 * @param array $vars The current less variables.
	 * @param array $instance The current widget instance.
	 *
	 * @return string The updated less content.
	 */
	public function add_less( $less, $vars, $instance ) {
		if (
			! empty( $less ) &&
			! empty( $instance['design']['image'] ) &&
			(
				! empty( $instance['design']['image']['file'] ) ||
				! empty( $instance['design']['image']['file_fallback'] )
			)
		) {
			$less .= file_get_contents( plugin_dir_path( __FILE__ ) . 'less/image.less' );
		}

		return $less;
	}
}
