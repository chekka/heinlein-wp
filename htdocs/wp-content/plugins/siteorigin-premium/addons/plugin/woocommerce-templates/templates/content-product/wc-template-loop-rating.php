<?php

class SiteOrigin_Premium_WooCommerce_Template_Loop_Rating extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'so-wc-template-loop-rating',
			__( 'Product Loop Rating', 'siteorigin-premium' ),
			array( 'description' => __( 'Display the product rating.', 'siteorigin-premium' ) ),
			array()
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( function_exists( 'woocommerce_template_loop_rating' ) ) {
			do_action( 'siteorigin_premium_wctb_archive_rating_before' );
			woocommerce_template_loop_rating();
			do_action( 'siteorigin_premium_wctb_archive_rating_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Loop_Rating' );
