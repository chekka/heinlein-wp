<?php

class SiteOrigin_Premium_WooCommerce_Template_Single_Sharing extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-single-sharing',
			__( 'Product Sharing', 'siteorigin-premium' ),
			array( 'description' => __( 'Adds a sharing location on the page for third-party plugins to make use of.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_single_sharing' ) ) {
			do_action( 'siteorigin_premium_wctb_single_product_sharing_after' );
			woocommerce_template_single_sharing();
			do_action( 'siteorigin_premium_wctb_single_product_sharing_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Single_Sharing' );
