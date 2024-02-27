<?php

class SiteOrigin_Premium_WooCommerce_Output_Product_Description extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-output-product-description',
			__( 'Product Description', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the full product description.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		do_action( 'siteorigin_premium_wctb_single_product_description_before' );
		woocommerce_product_description_tab();
		do_action( 'siteorigin_premium_wctb_single_product_description_after' );
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Output_Product_Description' );
