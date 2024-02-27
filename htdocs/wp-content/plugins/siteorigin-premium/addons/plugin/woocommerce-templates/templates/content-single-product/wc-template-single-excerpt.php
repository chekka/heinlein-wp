<?php

class SiteOrigin_Premium_WooCommerce_Template_Single_Excerpt extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-single-excerpt',
			__( 'Product Short Description', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the product short description.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_single_excerpt' ) ) {
			do_action( 'siteorigin_premium_wctb_single_product_excerpt_before' );
			woocommerce_template_single_excerpt();
			do_action( 'siteorigin_premium_wctb_single_product_excerpt_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Single_Excerpt' );
