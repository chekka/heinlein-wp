<?php
// Add compatibility for the NM Gift Registry and Wishlist plugin.
function siteorigin_premium_wctb_compat_nm_gift_registry() {
	$single_product_position = nmgr_get_option( 'add_to_wishlist_button_position_single', 35 );

	if ( is_numeric( $single_product_position ) ) {
		switch ( $single_product_position ) {
			case 1: // Before title.
				$hook = 'siteorigin_premium_wctb_single_product_title_before';
				break;

			case 6: // After title.
				$hook = 'siteorigin_premium_wctb_single_product_title_after';
				break;

			case 15: // After price.
				$hook = 'siteorigin_premium_wctb_single_product_price_after';
				break;

			case 25: // After excerpt.
				$hook = 'siteorigin_premium_wctb_single_product_excerpt_after';
				break;

			case 35: // After add to cart button.
				$hook = 'siteorigin_premium_wctb_add_to_cart_after';
				break;

			case 45: // After meta information.
				$hook = 'siteorigin_premium_wctb_single_product_meta_after';
				break;
		}

		if ( ! empty( $hook ) ) {
			add_action( $hook, 'nmgr_add_to_wishlist_button', (int) $single_product_position );
		}
	}
}
add_action( 'init', 'siteorigin_premium_wctb_compat_nm_gift_registry' );