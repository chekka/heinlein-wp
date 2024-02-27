<?php
class SiteOrigin_Premium_WooCommerce_Template_Single_Meta extends SiteOrigin_Widget {
	public $remove = array();

	public function __construct() {
		parent::__construct(
			'so-wc-template-single-meta',
			__( 'Product Meta', 'siteorigin-premium' ),
			array(
				'description' => __( 'Display the product category and SKU.', 'siteorigin-premium' ),
				'has_preview' => false,
				'panels_title' => false,
			),
			array(),
			array(
				'display' => array(
					'type' => 'checkboxes',
					'label' => __( 'Display', 'siteorigin-premium' ),
					'options' => array(
						'sku' => __( 'SKU', 'siteorigin-premium' ),
						'category' => __( 'Category', 'siteorigin-premium' ),
						'tags' => __( 'Tags', 'siteorigin-premium' ),
					),
					'default' => array(
						'category',
						'sku',
						'tags',
					),
				),
			)
		);
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
	}

	public function load_styles() {
		wp_register_style(
			'so-wc-template-single-meta',
			plugin_dir_url( __FILE__ ) . 'css/so-wc-meta.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	public function modify_instance( $instance ) {
		if (
			! empty( $instance ) &&
			! isset( $instance['display'] )
		) {
			$instance['display'] = array(
				'category',
				'sku',
				'tags',
			);
		}

		return $instance;
	}

	public function override_meta( $instance ) {
		if ( ! isset( $instance['display'] ) ) {
			return;
		}

		$display = array_flip( $instance['display'] );

		if ( ! isset( $display['sku'] ) ) {
			$this->remove['sku'] = true;
		}

		if ( ! isset( $display['category'] ) ) {
			$this->remove['category'] = true;
		}

		if ( ! isset( $display['tags'] ) ) {
			$this->remove['tags'] = true;
		}
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( function_exists( 'woocommerce_template_single_meta' ) ) {
			do_action( 'siteorigin_premium_wctb_single_product_meta_before' );
			wp_enqueue_style( 'so-wc-template-single-meta' );
			$this->override_meta( $instance );
			require plugin_dir_path( __FILE__ ) . 'meta.php';
			do_action( 'siteorigin_premium_wctb_single_product_meta_after' );
		}
		echo $args['after_widget'];
	}
}

register_widget( 'SiteOrigin_Premium_WooCommerce_Template_Single_Meta' );
