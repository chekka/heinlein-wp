<?php

class SiteOrigin_Premium_WooCommerce_Template_Loop_Product_Title extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-loop-product-title',
			__( 'Product Loop Title', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the product title.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_loop_product_title' ) ) {
			do_action( 'siteorigin_premium_wctb_archive_title_before' );
			woocommerce_template_loop_product_link_open();
			woocommerce_template_loop_product_title();
			woocommerce_template_loop_product_link_close();
			do_action( 'siteorigin_premium_wctb_archive_title_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Loop_Product_Title' );
