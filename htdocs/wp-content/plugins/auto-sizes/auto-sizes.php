<?php
/**
 * Plugin Name: Auto-sizes for Lazy-loaded Images
 * Plugin URI: https://github.com/WordPress/performance/tree/trunk/modules/images/auto-sizes
 * Description: This plugin implements the HTML spec for adding `sizes="auto"` to lazy-loaded images.
 * Requires at least: 6.3
 * Requires PHP: 7.0
 * Version: 1.0.0
 * Author: WordPress Performance Team
 * Author URI: https://make.wordpress.org/performance/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: auto-sizes
 *
 * @package auto-sizes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define the constant.
if ( defined( 'IMAGE_AUTO_SIZES_VERSION' ) ) {
	return;
}

define( 'IMAGE_AUTO_SIZES_VERSION', '1.0.0' );

require_once __DIR__ . '/hooks.php';