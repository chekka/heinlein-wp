<?php
if ( ! function_exists( 'so_woocommerce_templates_tp_product_image_flipper' ) ) {
	/**
	 * Modifies the display behavior of WooCommerce product image widget in favour of the TP Product Image Flipper widget.
	 *
	 * @param bool $status The current status of the product image flipper.
	 * @return bool The modified status.
	 */
	function so_woocommerce_templates_tp_product_image_flipper( $status ) {
		if ( $status && function_exists( 'tp_create_flipper_images' ) ) {
			$status = false;
			tp_create_flipper_images();
		}

		return $status;
	}
}
add_filter( 'so_woocommerce_templates_display_product_thumbnail', 'so_woocommerce_templates_tp_product_image_flipper', 10, 1 );