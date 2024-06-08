<?php
/*
Plugin Name: SiteOrigin Image Shape
Description: Transform your images with unique shapes and engaging effects, from shadows to hover enhancements, for standout visuals in the SiteOrigin Image Widget.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/image-shape
Tags: Widgets Bundle
Requires: so-widgets-bundle
*/

class SiteOrigin_Premium_Plugin_Image_Shape {
	private $shapes;

	public function __construct() {
		add_action( 'siteorigin_widgets_enqueue_admin_scripts_sow-image', array( $this, 'load_custom_shape_emitter' ) );
		add_filter( 'siteorigin_widgets_form_options_sow-image', array( $this, 'add_form_options' ) );
		add_filter( 'siteorigin_widgets_less_variables_sow-image', array( $this, 'add_less_variables' ), 10, 3 );
		add_filter( 'siteorigin_widgets_less_vars_sow-image', array( $this, 'add_less' ), 20, 3 );
		add_filter( 'siteorigin_widgets_image_shapes', array( $this, 'add_premium_shapes' ) );
		add_filter( 'siteorigin_widgets_image_shape_file_path', array( $this, 'premium_path' ), 10, 2 );
		add_filter( 'siteorigin_widgets_image_shape_file_url', array( $this, 'premium_url' ), 10, 2 );

		$this->shapes = include( plugin_dir_path( __FILE__ ) . 'data/premium-shapes.php' );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function load_custom_shape_emitter() {
		wp_enqueue_script(
			'so-image-shapes-custom-emitter',
			plugin_dir_url( __FILE__ ) . 'js/so-image-shapes-custom-emitter' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	public function add_form_options( $form_options ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		if ( isset( $form_options['image_shape'] ) ) {
			siteorigin_widgets_array_insert(
				$form_options['image_shape']['fields'],
				'repeat',
				array(
					'shape_custom' => array(
						'type' => 'media',
						'label' => __( 'Custom Shape', 'siteorigin-premium' ),
						'fallback' => true,
					),
				)
			);

			$form_options['image_shape']['fields']['shadow'] = array(
				'type' => 'section',
				'label' => __( 'Shadow', 'siteorigin-premium' ),
				'hide' => true,
				'state_handler' => array(
					'image_shape[enabled]' => array( 'show' ),
					'image_shape[disabled]' => array( 'hide' ),
				),
				'fields' => array(
					'enable' => array(
						'type' => 'checkbox',
						'label' => __( 'Enable', 'siteorigin-premium' ),
						'default' => false,
						'state_emitter' => array(
							'callback' => 'conditional',
							'args' => array(
								'image_shape_shadow[enabled]: val',
								'image_shape_shadow[disabled]: ! val',
							),
						),
					),
					'color' => array(
						'type' => 'color',
						'label' => __( 'Color', 'siteorigin-premium' ),
						'default' => 'rgba(0,0,0,0.15)',
						'alpha' => true,
						'state_handler' => array(
							'image_shape_shadow[enabled]' => array( 'show' ),
							'image_shape_shadow[disabled]' => array( 'hide' ),
						),
					),
					'offset_horizontal' => array(
						'type' => 'measurement',
						'label' => __( 'Horizontal Offset', 'siteorigin-premium' ),
						'default' => 0,
						'state_handler' => array(
							'image_shape_shadow[enabled]' => array( 'show' ),
							'image_shape_shadow[disabled]' => array( 'hide' ),
						),
					),
					'offset_vertical' => array(
						'type' => 'measurement',
						'label' => __( 'Vertical Offset', 'siteorigin-premium' ),
						'default' => '5px',
						'state_handler' => array(
							'image_shape_shadow[enabled]' => array( 'show' ),
							'image_shape_shadow[disabled]' => array( 'hide' ),
						),
					),
					'blur' => array(
						'type' => 'measurement',
						'label' => __( 'Blur', 'siteorigin-premium' ),
						'default' => '15px',
						'state_handler' => array(
							'image_shape_shadow[enabled]' => array( 'show' ),
							'image_shape_shadow[disabled]' => array( 'hide' ),
						),
					),
					'spread' => array(
						'type' => 'measurement',
						'label' => __( 'Spread', 'siteorigin-premium' ),
						'state_handler' => array(
							'image_shape_shadow[enabled]' => array( 'show' ),
							'image_shape_shadow[disabled]' => array( 'hide' ),
						),
					),
				),
			);

			$form_options['image_shape']['fields']['shadow_hover'] = array(
				'type' => 'section',
				'label' => __( 'Shadow Hover', 'siteorigin-premium' ),
				'hide' => true,
				'state_handler' => array(
					'image_shape[enabled]' => array( 'show' ),
					'image_shape[disabled]' => array( 'hide' ),
				),
				'fields' => array(
					'enable' => array(
						'type' => 'checkbox',
						'label' => __( 'Enable', 'siteorigin-premium' ),
						'default' => false,
						'state_emitter' => array(
							'callback' => 'conditional',
							'args' => array(
								'image_shape_shadow_hover[enabled]: val',
								'image_shape_shadow_hover[disabled]: ! val',
							),
						),
					),
					'color' => array(
						'type' => 'color',
						'label' => __( 'Color', 'siteorigin-premium' ),
						'default' => 'rgba(0,0,0,0.30)',
						'alpha' => true,
						'state_handler' => array(
							'image_shape_shadow_hover[enabled]' => array( 'show' ),
							'image_shape_shadow_hover[disabled]' => array( 'hide' ),
						),
					),
					'offset_horizontal' => array(
						'type' => 'measurement',
						'label' => __( 'Horizontal Offset', 'siteorigin-premium' ),
						'default' => 0,
						'state_handler' => array(
							'image_shape_shadow_hover[enabled]' => array( 'show' ),
							'image_shape_shadow_hover[disabled]' => array( 'hide' ),
						),
					),
					'offset_vertical' => array(
						'type' => 'measurement',
						'label' => __( 'Vertical Offset', 'siteorigin-premium' ),
						'default' => '5px',
						'state_handler' => array(
							'image_shape_shadow_hover[enabled]' => array( 'show' ),
							'image_shape_shadow_hover[disabled]' => array( 'hide' ),
						),
					),
					'blur' => array(
						'type' => 'measurement',
						'label' => __( 'Blur', 'siteorigin-premium' ),
						'default' => '15px',
						'state_handler' => array(
							'image_shape_shadow_hover[enabled]' => array( 'show' ),
							'image_shape_shadow_hover[disabled]' => array( 'hide' ),
						),
					),
					'spread' => array(
						'type' => 'measurement',
						'label' => __( 'Spread', 'siteorigin-premium' ),
						'state_handler' => array(
							'image_shape_shadow_hover[enabled]' => array( 'show' ),
							'image_shape_shadow_hover[disabled]' => array( 'hide' ),
						),
					),
				),
			);
		}

		return $form_options;
	}

	private static function generate_shadow( $settings, $setting ) {
		$box_shadow_offset_horizontal = ! empty( $settings['offset_horizontal'] ) ? $settings['offset_horizontal'] : 0;
		$box_shadow_offset_vertical = ! empty( $settings['offset_vertical'] ) ? $settings['offset_vertical'] : '5px';
		$box_shadow_blur = ! empty( $settings['blur'] ) ? $settings['blur'] : '15px';
		$box_shadow_spread = ! empty( $settings['spread'] ) ? $settings['spread'] : '';
		$box_shadow_color = $settings['color'];

		$box_shadow_default = $setting == 'shadow' ? 0.15 : 0.30;
		$box_shadow_opacity = isset( $settings['opacity'] ) && is_numeric( $settings['opacity'] ) ? min( 100, $settings['opacity'] ) / 100 : $box_shadow_default;

		return "~'drop-shadow( $box_shadow_offset_horizontal $box_shadow_offset_vertical $box_shadow_blur $box_shadow_spread $box_shadow_color )'";
	}

	public function add_less_variables( $less_variables, $instance, $widget ) {
		if ( empty( $instance['image_shape'] ) || ! $instance['image_shape']['enable'] ) {
			return $less_variables;
		}


		if ( $instance['image_shape']['shape'] == 'custom' ) {
			$src = siteorigin_widgets_get_attachment_image_src(
				$instance['image_shape']['shape_custom'],
				'full',
				! empty( $instance['image_shape']['shape_custom_fallback'] ) ? $instance['image_shape']['shape_custom_fallback'] : false
			);

			// If the user hasn't set a valid image, or fallback, default to circle.
			$src = ! empty( $src ) ? $src[0] : SiteOrigin_Widget_Image_Shapes::single()->get_image_shape( 'circle' );
			$less_variables['image_shape'] = 'url( "' . esc_url( $src ) . '" )';
		}

		if ( ! empty( $instance['image_shape']['shadow']['enable'] ) ) {
			$less_variables['image_shape_shadow'] = self::generate_shadow( $instance['image_shape']['shadow'], 'shadow' );
		}

		if ( ! empty( $instance['image_shape']['shadow_hover']['enable'] ) ) {
			$less_variables['image_shape_shadow_hover'] = self::generate_shadow( $instance['image_shape']['shadow_hover'], 'shadow_hover' );
		}


		return $less_variables;
	}

	public function add_less( $less, $vars, $instance ) {
		if ( empty( $instance['image_shape'] ) || ! $instance['image_shape']['enable'] ) {
			return $less;
		}

		$less .= file_get_contents( plugin_dir_path( __FILE__ ) . 'less/base.less' );

		return $less;
	}

	public function add_premium_shapes( $shapes ) {
		return array_merge(
			array(
				'custom' => __( 'Custom', 'siteorigin-premium' ),
			),
			$shapes,
			$this->shapes
		);
	}

	public function premium_path( $path, $shape ) {
		if ( isset( $this->shapes[ $shape ] ) ) {
			$path = plugin_dir_path( __FILE__ ) . 'shapes/';
		}
		return $path;
	}

	public function premium_url( $url, $shape ) {
		if ( isset( $this->shapes[ $shape ] ) ) {
			$url = plugin_dir_url( __FILE__ ) . 'shapes/';
		}
		return $url;
	}

}
