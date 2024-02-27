<?php

class SiteOrigin_Premium_WooCommerce_Template_Single_Title extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-single-title',
			__( 'Product Title', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the product title.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_single_title' ) ) {
			do_action( 'siteorigin_premium_wctb_single_product_title_before' );
			woocommerce_template_single_title();
			do_action( 'siteorigin_premium_wctb_single_product_title_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Single_Title' );
