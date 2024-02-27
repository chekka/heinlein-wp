<?php

class SiteOrigin_Premium_WooCommerce_Template_Loop_Price extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-loop-price',
			__( 'Product Loop Price', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the product price.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_loop_price' ) ) {
			do_action( 'siteorigin_premium_wctb_archive_price_before' );
			woocommerce_template_loop_price();
			do_action( 'siteorigin_premium_wctb_archive_price_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Loop_Price' );
