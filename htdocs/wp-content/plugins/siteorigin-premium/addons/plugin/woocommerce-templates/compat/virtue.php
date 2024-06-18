<?php
// Add compatibility for the Virtue theme.
function siteorigin_premium_wctb_compat_virtue_archive_item_before() {
	global $woocommerce_loop, $virtue_premium;

	if ( is_shop() || is_product_category() || is_product_tag() ) {
		if ( isset( $virtue_premium['product_cat_layout'] ) && ! empty( $virtue_premium['product_cat_layout'] ) ) {
			$product_cat_column = $virtue_premium['product_cat_layout'];
		} else {
			$product_cat_column = 4;
		}
		$woocommerce_loop['columns'] = $product_cat_column;
	} else {
		if ( empty( $woocommerce_loop['columns'] ) ) {
			$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
		}
		$product_cat_column = $woocommerce_loop['columns'];
	}

	if ( $product_cat_column == '1' ) {
		$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
	} elseif ( $product_cat_column == '2' ) {
		$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
	} elseif ( $product_cat_column == '3' ) {
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
	} elseif ( $product_cat_column == '6' ) {
		$itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
	} elseif ( $product_cat_column == '5' ) {
		$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-4 tcol-ss-6';
	}

	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		$woocommerce_loop['columns'] = $product_cat_column;
	}

	?>

	<div class="<?php echo esc_attr( $itemsize ); ?> kad_product">
	<?php
}
add_action( 'siteorigin_premium_wctb_archive_item_before', 'siteorigin_premium_wctb_compat_virtue_archive_item_before' );

function siteorigin_premium_wctb_compat_virtue_wctb_item_element( $element ) {
	return 'div';
}
add_filter( 'siteorigin_premium_wctb_item_element', 'siteorigin_premium_wctb_compat_virtue_wctb_item_element' );

function siteorigin_premium_wctb_compat_virtue_item_product_class() {
	return 'product-category grid_item';
}
add_filter( 'siteorigin_premium_wctb_item_product_class', 'siteorigin_premium_wctb_compat_virtue_item_product_class' );

function siteorigin_premium_wctb_compat_virtue_archive_item_after() {
	echo '</div>';
}
add_action( 'siteorigin_premium_wctb_archive_item_after', 'siteorigin_premium_wctb_compat_virtue_archive_item_after' );
