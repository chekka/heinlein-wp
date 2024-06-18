<?php

// Add compatibility for the WPC Smart Wishlist for WooCommerce plugin.
if ( function_exists( 'woosw_init' ) ) {
	add_action( 'woocommerce_before_main_content', 'siteorigin_premium_wctb_compat_woosw' );
	add_action( 'woocommerce_before_single_product', 'siteorigin_premium_wctb_compat_woosw' );
	function siteorigin_premium_wctb_compat_woosw() {
		siteorigin_premium_wctb_compat_wpc( 'wishlist' );
	}
}

// Add compatibility for the WPC Smart Compare for WooCommerce plugin.
if ( function_exists( 'woosc_init' ) ) {
	add_action( 'woocommerce_before_main_content', 'siteorigin_premium_wctb_compat_wpc' );
	add_action( 'woocommerce_before_single_product', 'siteorigin_premium_wctb_compat_wpc' );
}

function siteorigin_premium_wctb_compat_wpc( $plugin ) {
	if ( is_product() ) {
		if ( $plugin == 'wishlist' ) {
			$single_product_position = apply_filters( 'woosw_button_position_single', WPCleverWoosw::instance()::get_setting( 'button_position_single', apply_filters( 'woosw_button_position_single_default', '31' ) ) );
		} else {
			$single_product_position = apply_filters( 'woosc_button_position_single', WPCleverWoosc::instance()::get_setting( 'button_position_single', apply_filters( 'woosc_button_position_single_default', '31' ) ) );
		}

		switch ( $single_product_position ) {
			case 6: // Under title.
				$hook = 'siteorigin_premium_wctb_single_product_title_after';
				break;

			case 11: // Under rating.
				$hook = 'siteorigin_premium_wctb_single_product_rating_after';
				break;

			case 21: // Under excerpt.
				$hook = 'siteorigin_premium_wctb_single_product_description_after';
				break;

			case 29: // Above add to cart button.
				$hook = 'siteorigin_premium_wctb_add_to_cart_before';
				break;

			case 31: // Under add to cart button.
				$hook = 'siteorigin_premium_wctb_add_to_cart_after';
				break;

			case 41: // Under meta.
				$hook = 'siteorigin_premium_wctb_single_product_meta_after';
				break;

			case 51: // Under sharing.
				$hook = 'siteorigin_premium_wctb_single_product_sharing_after';
				break;

			default: // Legacy.
				$hook = 'siteorigin_premium_wctb_single_product_description_after';
				break;
		}
	} else {
		if ( $plugin == 'wishlist' ) {
			$archive_position = apply_filters( 'woosw_button_position_archive', WPCleverWoosw::instance()::get_setting( 'button_position_archive', apply_filters( 'woosw_button_position_archive_default', 'default' ) ) );
		} else {
			$archive_position = apply_filters( 'woosc_button_position_archive', WPCleverWoosc::instance()::get_setting( 'button_position_archive', apply_filters( 'woosc_button_position_archive_default', 'default' ) ) );
		}

		if ( ! empty( $archive_position ) ) {
			switch ( $archive_position ) {
				case 'before_title':
					$hook = 'siteorigin_premium_wctb_archive_title_before';
					break;

				case 'after_title':
					$hook = 'siteorigin_premium_wctb_archive_title_after';
					break;

				case 'after_rating':
					$hook = 'siteorigin_premium_wctb_archive_rating_after';
					break;

				case 'after_price':
					$hook = 'siteorigin_premium_wctb_archive_price_after';
					break;

				case 'before_add_to_cart':
					$hook = 'siteorigin_premium_wctb_archive_add_to_cart_before';
					break;

				case 'after_add_to_cart':
					$hook = 'siteorigin_premium_wctb_archive_add_to_cart_after';
					break;
				default:
					$hook = 'siteorigin_premium_wctb_archive_add_to_cart_after';
					break;
			}
		}
	}

	if ( ! empty( $hook ) ) {
		add_action(
			$hook,
			array(
				(
					$plugin == 'wishlist' ? WPCleverWoosw::instance() : WPCleverWoosc::instance()
				),
				'add_button',
			)
		);
	}
}
