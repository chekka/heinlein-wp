<?php
// Add compatibility for the Black Studio TinyMCE plugin.
// Load Black Studio TinyMCE's Visual Editor widget assets while using the WCTB.
function siteorigin_premium_wctb_compat_black_studio( $enabled_pages ) {
	global $pagenow;

	if (
		$pagenow == 'admin.php' &&
		! empty( $_GET ) &&
		! empty( $_GET['page'] ) &&
		$_GET['page'] == 'so-wc-templates'
	) {
		$enabled_pages[] = 'admin.php';
	}

	return $enabled_pages;
}
add_filter( 'black_studio_tinymce_enable_pages', 'siteorigin_premium_wctb_compat_black_studio' );
