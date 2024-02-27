<?php

/**
 * Adds a field to the SiteOrigin Google Maps widget that allows the user to easily bulk add addresses.
 *
 * Class SiteOrigin_Widget_Field_Bulk_Addresses
 */
class SiteOrigin_Widget_Field_Bulk_addresses extends SiteOrigin_Widget_Field_Base {

protected function render_field( $attachments, $instance ) {
		?>
		<a href="#" class="button so-bulk-addresses-field-add-bulk">Bulk add addresses</a>
		<div class="so-bulk-addresses-field-add-wrapper" style="display: none;">
			<textarea></textarea>
			<a href="#" class="button button-primary disabled">Add addresses</a>
		</div>
		<?php
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'so-bulk-addresses-field',
			plugin_dir_url( __FILE__ ) . 'js/bulk-addresses.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_localize_script(
			'so-bulk-addresses-field',
			'soBulkAddressesField',
			array(
				'error' => __( 'Unable to add marker for address', 'siteorigin-premium' ),
			)
		);

		wp_enqueue_style(
			'so-bulk-addresses-field',
			plugin_dir_url( __FILE__ ) . 'css/bulk-addresses.css',
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	protected function sanitize_field_input( $value, $instance ) {
		return;
	}
}
