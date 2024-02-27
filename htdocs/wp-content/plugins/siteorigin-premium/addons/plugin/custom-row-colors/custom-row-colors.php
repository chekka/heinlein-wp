<?php
/*
Plugin Name: SiteOrigin Custom Row Colors
Description: Organize your Page Builder rows with custom background colors.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/custom-row-colors/
Tags: Page Builder
Requires: siteorigin-panels
Video:
*/

class SiteOrigin_Premium_Plugin_Custom_Row_Colors {
	public function __construct() {
		add_filter( 'siteorigin_panels_admin_row_colors', array( $this, 'override_row_colors' ), 5 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function get_settings_form() {
		if ( method_exists( 'SiteOrigin_Panels_Admin', 'get_row_colors' ) ) {
			$row_colors = SiteOrigin_Panels_Admin::get_row_colors();

			$settings = array(
				'row_colors' => array(
					'type' => 'repeater',
					'label' => __( 'Row Colors', 'siteorigin-premium' ),
					'item_label' => array(
							'selector' => "[id*='name']",
							'update_event' => 'change',
							'value_method' => 'val',
					),
					'item_name' => __( 'Row Color', 'siteorigin-premium' ),
					'default' => $row_colors,
					'fields' => array(
						'name' => array(
							'type' => 'text',
							'label' => __( 'Name', 'siteorigin-premium' ),
							'required' => __( 'Row Colors must have a name to be useable.', 'siteorigin-premium' ),
						),
						'inactive' => array(
							'type' => 'color',
							'label' => __( 'Inactive Color', 'siteorigin-premium' ),
						),
						'active' => array(
							'type' => 'color',
							'label' => __( 'Active Color', 'siteorigin-premium' ),
						),
						'cell_divider' => array(
							'type' => 'color',
							'label' => __( 'Column Divider', 'siteorigin-premium' ),
						),
						'cell_divider_hover' => array(
							'type' => 'color',
							'label' => __( 'Column Divider Hover', 'siteorigin-premium' ),
						),
					),
				),
			);
		} elseif ( class_exists( 'SiteOrigin_Widget_Field_Html' ) && defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
			$settings = array(
				'html' => array(
					'type' => 'html',
					'markup' => sprintf( __( 'This addon requires SiteOrigin Page Builder 2.17.0. You have version %s installed. Please update SiteOrigin Page Builder.', 'siteorigin-premium' ), SITEORIGIN_PANELS_VERSION ),
				),
			);
		}

		if ( ! empty( $settings ) ) {
			return new SiteOrigin_Premium_Form(
				'so-addon-custom-row-colors-settings',
				$settings
			);
		}
	}

	public function override_row_colors( $row_colors ) {
		if (
			empty( $_GET['action'] ) ||
			(
				$_GET['action'] != 'so_premium_addon_settings_form' &&
				$_GET['action'] != 'so_premium_addon_settings_save'
			)
		) {
			$premium_options = SiteOrigin_Premium_Options::single();
			$settings = $premium_options->get_settings( 'plugin/custom-row-colors', false );

			if ( ! empty( $settings ) ) {
				$row_colors = array();

				// To avoid issues, we don't prefix the defualt PB row colors.
				$default_colors = array(
					'soft-blue' => true,
					'soft-red' => true,
					'grayish-violet' => true,
					'lime-green' => true,
					'desaturated-yellow' => true,
				);

				foreach ( $settings['row_colors'] as $color ) {
					// If no name is set, don't add color.
					if ( empty( $color['name'] ) ) {
						continue;
					}

					$color_name = sanitize_title( $color['name'] );
					$color_name = ! empty( $default_colors[ $color_name ] ) ? $color_name : 'custom-row-color-' . $color_name;
					$color['name'] = $color_name;
					$row_colors[ $color_name ] = $color;
				}
			}
		}

		return $row_colors;
	}
}
