<?php
/*
Plugin Name: SiteOrigin Multiple Media
Description: Streamline your workflow by uploading multiple images simultaneously to Sliders, Grids, and Hero frames, quickly creating visually stunning websites.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/multiple-media
Tags: Page Builder
Requires: so-widgets-bundle
*/

class SiteOrigin_Premium_Plugin_Multiple_Media {
	public function __construct() {
		add_filter( 'siteorigin_widgets_form_options', array( $this, 'add_multiple_media_image_field' ), 10, 2 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Build the multiple_media form field array.
	 *
	 * @param $field string The ID of the repeater form field.
	 * @param $field string The media form field inside of the repeater that'll be set when the user adds new images.
	 * @param $args mixed The optional description for the Multiple Media field. If not specified, a default will be used.
	 *
	 * @return array
	 **/
	private function multiple_media_field( $field, $setting, $description = false ) {
		if ( ! $description ) {
			$description = __( 'Add multiple images using the Add Media button above or add individual images using the Add link below.', 'siteorigin-premium' );
		}

		return array(
			'multiple_media' => array(
				'type' => 'multiple_media',
				'library' => 'all',
				'description' => $description,
				'repeater' => array(
					'field' => $field,
					'setting' => $setting,
				),
			),
		);
	}

	public function add_multiple_media_image_field( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return array();
		}

		switch ( $widget->id_base ) {
			case 'sow-image-grid':
				$bulk_field = $this->multiple_media_field( 'images', 'image' );
				break;

			case 'sow-simple-masonry':
				$bulk_field = $this->multiple_media_field( 'items', 'image' );
				break;

			case 'sow-slider':
				$bulk_field = $this->multiple_media_field(
					'frames',
					'background_image',
					__( 'Add multiple Slider frames using the Add Media button above or add individual images using the Add link below.', 'siteorigin-premium' )
				);
				break;

			case 'sow-hero':
				$bulk_field = $this->multiple_media_field(
					'frames',
					'background .siteorigin-widget-field-image',
					__( 'Add multiple Hero frames using the Add Media button above or add individual images using the Add link below.', 'siteorigin-premium' )
				);
				break;

			case 'sow-layout-slider':
				$bulk_field = $this->multiple_media_field(
					'frames',
					'background .siteorigin-widget-field-image',
					__( 'Add multiple Slider frames using the Add Media button above or add individual images using the Add link below.', 'siteorigin-premium' )
				);
				break;
		}

		if ( isset( $bulk_field ) ) {
			siteorigin_widgets_array_insert( $form_options, $bulk_field['multiple_media']['repeater']['field'], $bulk_field );
		}

		return $form_options;
	}
}
