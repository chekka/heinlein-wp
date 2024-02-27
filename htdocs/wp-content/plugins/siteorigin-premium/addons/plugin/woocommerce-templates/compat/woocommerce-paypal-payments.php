<?php
// Add compatibility for the WooCommerce PayPal Payments plugin.
// Check if we need to override the WooCommerce PayPal Payments render hook on this page or not.
function siteorigin_premium_wctb_compat_woocommerce_paypal_payments_setup() {
	$ppcp_settings = get_option( 'woocommerce-ppcp-settings' );

	if (
		! empty( $ppcp_settings ) &&
		(
			// Don't appear override filter unless PayPal is connected.
			! empty( $ppcp_settings['client_id'] ) ||
			! empty( $ppcp_settings['client_id_sandbox'] )
		) &&
		! empty( $ppcp_settings['enabled'] ) &&
		! empty( $ppcp_settings['button_single_product_enabled'] ) &&
		! empty( $ppcp_settings['message_product_enabled'] )
	) {
		global $wp_query;

		if ( ! is_admin() && get_query_var( 'post_type' ) == 'product' ) {
			$product = get_page_by_path( get_query_var( 'product' ), OBJECT, 'product' );

			if ( ! empty( $product ) ) {
				$has_template = get_post_meta( $product->ID, 'so_wc_template_post_id', true );
				// Does this product page have a template set?
				if ( ! empty( $has_template ) ) {
					$override = true;
				} else {
					// Is there a global template set?
					$so_wc_templates = get_option( 'so-wc-templates' );

					if (
						! empty( $so_wc_templates['content-single-product'] ) &&
						! empty( $so_wc_templates['content-single-product']['post_id'] )
					) {
						$override = true;
					}
				}
			}

			if ( ! empty( $override ) ) {
				add_filter(
					'woocommerce_paypal_payments_single_product_renderer_hook',
					'siteorigin_premium_wctb_compat_woocommerce_paypal_payments'
				);
			}
		}
	}
}
add_action( 'parse_query', 'siteorigin_premium_wctb_compat_woocommerce_paypal_payments_setup' );

	
// Move WooCommerce PayPal Payments buttons after Add to Cart button.
function siteorigin_premium_wctb_compat_woocommerce_paypal_payments( $filter ) {
	return 'siteorigin_premium_wctb_add_to_cart_after';
}