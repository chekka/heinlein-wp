<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
// It's possible the widget may be loaded before $product is set.
// So to avoid a fatal, we'll just return if $product is empty.
if ( empty( $product ) ) {
	return;
}
?>
<div class="product_meta">

	<?php
	do_action( 'woocommerce_product_meta_start' );

	if ( empty( $this->remove['sku'] ) ) {
		if (
			wc_product_sku_enabled() &&
			(
				$product->get_sku() ||
				$product->is_type( 'variable' )
			)
		) {
			?>
			<span class="sku_wrapper">
				<?php esc_html_e( 'SKU:', 'woocommerce' ); ?>
				<span class="sku">
					<?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?>
				</span>
			</span>
			<?php
		}
	}

	if ( empty( $this->remove['category'] ) ) {
		echo wc_get_product_category_list(
			$product->get_id(),
			', ',
			'<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ',
			'</span>'
		);
	}

	if ( empty( $this->remove['tags'] ) ) {
		echo wc_get_product_tag_list(
			get_the_ID(),
			', ',
			'<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ',
			'</span>'
		);
	}

	do_action( 'woocommerce_product_meta_end' );
	?>

</div>
