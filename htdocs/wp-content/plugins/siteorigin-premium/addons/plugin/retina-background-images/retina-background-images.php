<?php
/*
Plugin Name: SiteOrigin Retina Background Images
Description: Deliver crystal clear visuals across your site by adding Retina ready background images to widgets, cells, and rows for sharp, high-resolution displays.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/retina-background-images/
Tags: Page Builder
Requires: siteorigin-panels
*/

class SiteOrigin_Premium_Plugin_Retina_background_images {
	public function __construct() {
		add_filter( 'siteorigin_panels_general_style_fields', array( $this, 'add_styles' ), 10, 3 );
		add_filter( 'siteorigin_panels_general_style_attributes', array( $this, 'add_retina_css' ), 11, 2 );
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'enqueue_admin_assets' ), 20 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_styles( $fields, $post_id, $args ) {
		$fields['retina_background_image'] = array(
			'name' => __( 'Retina Background Image', 'siteorigin-premium' ),
			'type' => 'image',
			'group' => 'design',
			'priority' => 8,
		);

		$fields['retina_background_image_size'] = array(
			'name' => __( 'Retina Background Image Size', 'siteorigin-premium' ),
			'type' => 'image_size',
			'group' => 'design',
			'priority' => 9,
		);

		return $fields;
	}

	private function get_image_Url( $name, $styles ) {
		if ( ! empty( $styles[ $name ] ) ) {
			$image_size_name = ! empty( $name ) ? 'background' : $name;
			$url = SiteOrigin_Panels_Styles::get_attachment_image_src(
				$styles[ $name ],
				! empty( $styles[ $image_size_name . '_image_size' ] ) ? $styles[ $image_size_name . '_image_size' ] : 'full'
			);

			if ( ! empty( $url ) ) {
				$url = $url[0];
			}
		}

		if ( empty( $url ) && ! empty( $styles[ $name . 'fallback' ] ) ) {
			$url = $styles[ $name . 'fallback' ];
		}

		return esc_url( $url );
	}

	public function add_retina_css( $attributes, $styles ) {
		if (
			(
				! empty( $styles['background_image_attachment'] ) ||
				! empty( $styles['background_image_attachment_fallback'] )
			) &&
			(
				! empty( $styles['retina_background_image'] ) ||
				! empty( $styles['retina_background_image_fallback'] )
			)
		) {
			$css = 'url("' . self::get_image_Url( 'background_image_attachment', $styles ) . '") 1x,
			url("' . self::get_image_Url( 'retina_background_image', $styles ) . '") 2x);';

			// Convert the style field to an array if needed.
			if ( empty( $attributes['style'] ) ) {
				$attributes['style'] = array();
			} elseif ( ! is_array( $attributes['style'] ) ) {
				$style = $attributes['style'];
				$attributes['style'] = array();
				$attributes['style'][] = $style;
			}
			$attributes['style'][] = 'background-image: -webkit-image-set(' . $css;
			$attributes['style'][] = 'background-image: image-set(' . $css;
		}

		return $attributes;
	}

	public function enqueue_admin_assets() {
		if ( ! wp_script_is( 'siteorigin-premium-retina-background-images-addon' ) ) {
			wp_enqueue_script(
				'siteorigin-premium-retina-background-images-addon',
				plugin_dir_url( __FILE__ ) . 'js/script' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array( 'jquery' ),
				SITEORIGIN_PREMIUM_VERSION
			);
		}
	}
}
