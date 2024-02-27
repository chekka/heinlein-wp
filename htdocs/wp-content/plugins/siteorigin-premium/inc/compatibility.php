<?php

/**
 * Class SiteOrigin_Premium_Compatibility
 */
class SiteOrigin_Premium_Compatibility {
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ), 1 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init() {
		// Prevent NativeChurch from loading premium again.
		if ( defined( 'NATIVECHURCH_CORE__PLUGIN_PATH' ) ) {
			remove_action( 'plugins_loaded', 'siteorigin_p_init' );
		}
	}
}
