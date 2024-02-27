<?php
/*
Plugin Name: SiteOrigin Bulk Addresses
Description: Add multiple Map Marker addresses in one go to the SiteOrigin Google Maps Widget.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/bulk-addresses/
Tags: Widgets Bundle
Requires: so-widgets-bundle/google-map
*/

class SiteOrigin_Premium_Plugin_bulk_address {

	public function __construct() {
		add_filter( 'siteorigin_widgets_form_options_sow-google-map', array( $this, 'add_field' ) );
		add_filter( 'siteorigin_widgets_field_registered_class_paths', array( $this, 'siteorigin_override_field' ) );
	}

	public static function single() {
		static $single;
		return empty( $single ) ? $single = new self() : $single;
	}

	public function siteorigin_override_field( $class_paths ) {
		$class_paths['base'][] = plugin_dir_path( __FILE__ ) . 'fields/';

		return $class_paths;
	}

	public function add_field( $form_options ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$setting = array(
			'name' => __( 'Bulk', 'siteorigin-premium' ),
			'type' => 'bulk_addresses',
		);

		if ( version_compare( SOW_BUNDLE_VERSION, '1.46.7', '<' ) ) {
			$setting['description'] = sprintf( __( "To bulk add addresses, you must have SiteOrigin Widgets Bundle 1.46.7 or later installed. You have version %s installed.", 'siteorigin-premium' ), SOW_BUNDLE_VERSION );
		}

		siteorigin_widgets_array_insert( $form_options['markers']['fields'], 'marker_positions', array(
			'bulk_addresses' => $setting,
		) );

		return $form_options;
	}
}
