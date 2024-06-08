<?php
/*
Plugin Name: SiteOrigin Custom Palette
Description: Effortlessly customize your site's color scheme, creating a unique palette for Page Builder and Widgets Bundle, ensuring a harmonious visual experience.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/custom-palette/
Tags: Page Builder, Widgets Bundle
Video:
*/

class SiteOrigin_Premium_Plugin_Custom_Palette {
	public function __construct() {
		add_filter( 'siteorigin_widget_color_palette', array( $this, 'add_colors' ), 20 );
		add_filter( 'siteorigin_panels_wpcolorpicker_options', array( $this, 'add_page_builder_colors' ), 20 );
	}

	public static function single() {
		static $single;
		return empty( $single ) ? $single = new self() : $single;
	}

	public function get_settings_form() {
		return new SiteOrigin_Premium_Form(
			'so-addon-custom-palette-settings',
			array(
				'palettes' => array(
					'type' => 'repeater',
					'label' => __( 'Colors', 'siteorigin-premium' ),
					'item_label' => array(
						'selector' => "[id*='color']",
						'update_event' => 'change',
						'value_method' => 'val',
					),
					'item_name' => __( 'Color', 'siteorigin-premium' ),
					// Default colour values from https://github.com/Automattic/Iris/blob/master/src/iris.js#L256
					'default' => array(
						array(
							'color' => '#000',
						),
						array(
							'color' => '#fff',
						),
						array(
							'color' => '#d33',
						),
						array(
							'color' => '#d93',
						),
						array(
							'color' => '#ee2',
						),
						array(
							'color' => '#81d742',
						),
						array(
							'color' => '#1e73be',
						),
						array(
							'color' => '#8224e3',
						),
					),
					'fields' => array(
						'color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'siteorigin-premium' ),
						),
					),
				),
			)
		);
	}

	public function add_colors( $colors = array() ) {
		if (
			empty( $_GET['action'] ) ||
			(
				$_GET['action'] != 'so_premium_addon_settings_form' &&
				$_GET['action'] != 'so_premium_addon_settings_save'
			)
		) {
			$premium_options = SiteOrigin_Premium_Options::single();
			$settings = $premium_options->get_settings( 'plugin/custom-palette' );

			if ( ! empty( $settings ) && ! empty( $settings['palettes'] ) ) {
				$palettes = array();

				foreach ( $settings['palettes'] as $color ) {
					// Confirm a valid color is set.
					if ( empty( $color['color'] ) ) {
						continue;
					}
					$palettes[] = $color['color'];
				}

				if ( ! empty( $palettes ) ) {
					$palettes = array_map( 'sanitize_hex_color', $palettes );
				}

				// If something else has set custom colors, merge them with ours.
				$colors = ! empty( $colors ) && ! empty( $palettes ) ? array_merge( $colors, $palettes ) : $palettes;
			}
		}

		return $colors;
	}

	public function add_page_builder_colors( $options ) {
		$palettes = $this->add_colors();

		if ( ! empty( $palettes ) ) {
			// If something else has set custom colors, merge them with ours.
			$options['palettes'] = ! empty( $options['palettes'] ) ? array_merge( $options['palettes'], $palettes ) : $palettes;
		}

		return $options;
	}
}
