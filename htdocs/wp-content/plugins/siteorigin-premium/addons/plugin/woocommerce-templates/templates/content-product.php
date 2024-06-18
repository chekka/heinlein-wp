<?php

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$loop_el = apply_filters( 'siteorigin_premium_wctb_item_element', 'li' );

do_action( 'siteorigin_premium_wctb_archive_item_before' );
?>
<<?php echo esc_html( $loop_el ); ?>
	<?php
	wc_product_class(
		apply_filters(
			'siteorigin_premium_wctb_item_product_class',
			'',
			'content-product'
		),
		$product
	);
	?>
>
	<?php

	// If the user has created and enabled a Product Archive Page Builder layout we load and render it here.
	$template_post_id = get_query_var( 'wctb_template_id' );

	if ( ! empty( $template_post_id ) ) {
		echo SiteOrigin_Panels_Renderer::single()->render( $template_post_id );
	}

	?>
</<?php echo esc_html( $loop_el ); ?>>

<?php
do_action( 'siteorigin_premium_wctb_archive_item_after' );
