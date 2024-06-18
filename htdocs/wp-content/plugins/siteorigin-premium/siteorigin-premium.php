<?php
/*
Plugin Name: SiteOrigin Premium
Description: A collection of powerful addons that enhance every aspect of SiteOrigin plugins and themes.
Version: 1.63.0
Requires at least: 4.7
Tested up to: 6.5.2
Requires PHP: 7.0.0
Author: SiteOrigin
Text Domain: siteorigin-premium
Domain Path: /lang/
Author URI: https://siteorigin.com
Plugin URI: https://siteorigin.com/downloads/premium
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
*/

define( 'SITEORIGIN_PREMIUM_VERSION', '1.63.0' );
define( 'SITEORIGIN_PREMIUM_JS_SUFFIX', '.min' );
define( 'SITEORIGIN_PREMIUM_DIR', plugin_dir_path( __FILE__ ) );
define( 'SITEORIGIN_PREMIUM_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( 'SiteOrigin_Premium' ) ) {
	class SiteOrigin_Premium {
		const REPLACE_TEASERS = true;

		public static $js_suffix;

		public static $default_active = array();

		private $assets_setup = false;

		/**
		 * @var SiteOrigin_Premium_Updater
		 */
		private $updater;

		public function __construct() {
			// Register the autoloader
			spl_autoload_register( array( $this, 'autoloader' ) );

			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_addons' ), 15 );
			add_action( 'after_setup_theme', array( $this, 'load_theme_addons' ), 15 );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_common_scripts' ), 4 );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_common_scripts' ) );
			add_filter( 'siteorigin_add_installer', array( $this, 'add_installer_migrate' ), 9 );

			if ( self::REPLACE_TEASERS ) {
				// This removes teaser fields from the settings.
				add_filter( 'siteorigin_settings_display_teaser', '__return_false' );

				// And we create a new handler to add the field in the case of teasers.
				add_action( 'siteorigin_settings_add_teaser_field', array( $this, 'handle_teaser_field' ), 10, 6 );
			}

			if ( ! self::is_theme_mode() ) {
				add_action( 'init', array( $this, 'setup_updater' ) );
				add_action( 'admin_init', array( $this, 'load_metabox' ) );

				// Initialize all the extra components.
				SiteOrigin_Premium_Admin_Notices::single();
				SiteOrigin_Premium_Options::single();

				add_action( 'admin_init', array( $this, 'plugin_version_check' ) );

				add_action( 'siteorigin_premium_update_check', array( $this, 'check_license' ) );
			}

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
			add_filter( 'edd_sl_api_request_verify_ssl', '__return_false' );

			SiteOrigin_Premium_Compatibility::single();
		}

		/**
		 * Create the singleton of SiteOrigin Premium
		 *
		 * @return SiteOrigin_Premium
		 */
		public static function single() {
			static $single;

			return empty( $single ) ? $single = new self() : $single;
		}

		public static function autoloader( $classname ) {
			if ( preg_match( '/^SiteOrigin_Premium_(Theme_|Plugin_)?([A-Za-z_0-9]*)/', $classname, $matches ) ) {
				if ( ! empty( $matches[1] ) ) {
					$addon_type = strtolower( trim( $matches[1], '_' ) );
					$filename = SITEORIGIN_PREMIUM_DIR . '/addons/' . $addon_type . '/';
					$addon_id = strtolower( str_replace( '_', '-', $matches[2] ) );
					$filename .= $addon_id . '/' . $addon_id . '.php';
				} elseif ( $matches[2] == 'Options' ) {
					$filename = SITEORIGIN_PREMIUM_DIR . '/admin/options.php';
				} elseif ( $matches[2] == 'Metabox' ) {
					$filename = SITEORIGIN_PREMIUM_DIR . '/admin/metabox.php';
				} else {
					$filename = SITEORIGIN_PREMIUM_DIR . '/inc/';
					$filename .= strtolower( str_replace( '_', '-', $matches[2] ) ) . '.php';
				}

				if ( file_exists( $filename ) ) {
					include $filename;
				}
			}
		}

		public function setup_updater() {
			// Only set up the updater if the current user is actually able to
			// update the plugin.
			$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;

			if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
				return;
			}

			$key = get_option( 'siteorigin_premium_key' );

			if ( empty( $key ) ) {
				return;
			}

			// Set up the updater if the user has entered a key
			$this->updater = new SiteOrigin_Premium_Updater(
				esc_url( self::update_url() ),
				__FILE__,
				array(
					'version' => SITEORIGIN_PREMIUM_VERSION,
					'license' => trim( $key ),
					'item_id' => SiteOrigin_Premium_EDD_Actions::EDD_ITEM_ID,
					'author' => 'SiteOrigin',
					'beta' => false,
					'url' => home_url(),
				)
			);
		}

		/**
		 * Check for SiteOrigin Premium plugin updates, and trigger action to allow for migration.
		 */
		public function plugin_version_check() {
			$cached_version = get_option( 'siteorigin_premium_version' );

			// Account for potential dev build version mismatch.
			if ( strpos( $cached_version, '-' ) !== false ) {
				$cached_version = explode( '-', $cached_version )[0];
			}

			if ( empty( $cached_version ) || version_compare( $cached_version, SITEORIGIN_PREMIUM_VERSION, '<' ) ) {
				update_option( 'siteorigin_premium_version', SITEORIGIN_PREMIUM_VERSION );
				do_action( 'siteorigin_premium_version_update', SITEORIGIN_PREMIUM_VERSION, $cached_version );
			}
		}

		/**
		 * Checks the license's status and activates any required notices.
		 */
		public function check_license() {
			$license = new SiteOrigin_Premium_License( get_option( 'siteorigin_premium_key' ) );
			$status = $license->check_license_key();
			$notices = SiteOrigin_Premium_Admin_Notices::single();
			// Clear notices in case user has renewed their license and it is now valid and not expired.
			$notices->clear_notices();

			if (
				! empty( $status ) &&
				! isset( $status->first ) &&
				$notices->has_notice( $status )
			) {
				$notices->activate_notice( $status );
			}
		}

		public function add_installer_migrate( $status ) {
			return apply_filters( 'siteorigin_premium_add_installer', $status );
		}

		public function init() {
			load_plugin_textdomain(
				'siteorigin-premium',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/lang/'
			);

			// Load the Installer if it's not already active.
			if ( ! class_exists( 'SiteOrigin_Installer' ) ) {
				include SITEORIGIN_PREMIUM_DIR . 'inc/installer/siteorigin-installer.php';
			}

			include SITEORIGIN_PREMIUM_DIR . 'inc/gate.php';
		}

		/**
		 * Load any addons for the Widgets Bundle.
		 */
		public function load_plugin_addons() {
			$active = $this->get_active_addons();

			foreach ( $active as $id => $status ) {
				if ( ! $status ) {
					continue;
				}

				$this->load_addon( $id );
			}
		}

		/**
		 * Load supported and activated addons for themes.
		 */
		public function load_theme_addons() {
			global $_wp_theme_features;

			if ( empty( $_wp_theme_features ) || ! is_array( $_wp_theme_features ) ) {
				return;
			}

			foreach ( array_keys( $_wp_theme_features ) as $feature ) {
				if ( ! preg_match( '/siteorigin-premium-(.+)/', $feature, $matches ) ) {
					continue;
				}

				if ( ! isset( $_wp_theme_features[$feature][0] ) ) {
					continue;
				}

				$feature_args = $_wp_theme_features[$feature][0];

				if ( empty( $feature_args['enabled'] ) ) {
					continue;
				}

				$feature_name = $matches[1];
				$this->load_addon( 'theme/' . $feature_name );
			}
		}

		/**
		 * Loads a specific addon.
		 *
		 * @param string $id The ID of the addon to load. The ID should be in the format 'type/id'.
		 *
		 * @return mixed The instance of the addon if it exists and could be loaded. Otherwise, false will be returned.
		 */
		public function load_addon( $id ) {
			if ( empty( $id ) || ! is_string( $id ) ) {
				return false;
			}

			$classname = 'SiteOrigin_Premium_';
			list( $addon_type, $addon_id ) = explode( '/', $id, 2 );
			$classname .= ucfirst( $addon_type ) . '_';
			$classname .= implode( '_', array_map( 'ucfirst', explode( '-', $addon_id ) ) );

			if ( ! class_exists( $classname, true ) ) {
				return false;
			}

			return call_user_func( array( $classname, 'single' ) );
		}

		/**
		 * Handle the teaser field
		 *
		 * @param SiteOrigin_Settings $settings
		 * @param string              $section
		 * @param string              $id
		 * @param string              $type
		 * @param string              $label
		 * @param array               $args
		 */
		public function handle_teaser_field( $settings, $section, $id, $type, $label, $args ) {
			if ( method_exists( $settings, 'add_field' ) ) {
				$settings->add_field( $section, $id, $type, $label, $args );
			}
		}

		/**
		 * Get all the active addons
		 *
		 * @return mixed|void
		 */
		public function get_active_addons() {
			$active_addons = get_option( 'siteorigin_premium_active', array() );
			$active_addons = wp_parse_args( $active_addons, self::$default_active );

			return $active_addons;
		}

		/**
		 * Set the addon active state
		 *
		 * @param bool|true $active
		 */
		public function set_addon_active( $id, $active = true ) {
			// Check that the addon exists
			list( $addon_section, $addon_id ) = explode( '/', $id, 2 );
			$addon_id = sanitize_file_name( $addon_id );

			$active_addons = $this->get_active_addons();
			$filename = SiteOrigin_Premium::dir_path( __FILE__ ) . 'addons/' . $addon_section . '/' . $addon_id . '/' . $addon_id . '.php';

			if ( file_exists( $filename ) ) {
				$active_addons[ $id ] = $active;
			} else {
				unset( $active_addons[ $id ] );
			}

			update_option( 'siteorigin_premium_active', $active_addons );
		}

		/**
		 * Check if the addon is active
		 *
		 * @return bool
		 */
		public function is_addon_active( $addon_id ) {
			$active_addons = $this->get_active_addons();

			return ! empty( $active_addons[$addon_id] );
		}

		public function register_common_scripts() {
			if ( $this->assets_setup ) {
				return;
			}
			$this->assets_setup = true;

			// We only register scripts on the frontend, and the Block Editor.
			if ( is_admin() ) {
				$current_screen = get_current_screen();

				if (
					! empty( $current_screen ) &&
					method_exists( $current_screen, 'is_block_editor' ) &&
					! $current_screen->is_block_editor()
				) {
					// Not the Block Editor, bail.
					return;
				}
			}

			wp_register_style(
				'siteorigin-premium-animate',
				SiteOrigin_Premium::dir_url( __FILE__ ) . 'css/animate' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.css',
				array( ),
				SITEORIGIN_PREMIUM_VERSION
			);

			wp_register_script(
				'siteorigin-premium-animate',
				SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/animate' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array( 'jquery' ),
				SITEORIGIN_PREMIUM_VERSION
			);

			wp_register_script(
				'siteorigin-premium-map-user-location',
				SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/map-user-location' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array( 'jquery' ),
				SITEORIGIN_PREMIUM_VERSION
			);

			if ( $this->use_new_parallax() ) {
				if ( ! wp_script_is( 'simpleParallax', 'registered' ) ) {
					wp_register_script(
						'siteorigin-setup-parallax',
						SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/setup-parallax' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
						array(),
						SITEORIGIN_PREMIUM_VERSION
					);

					wp_register_script(
						'simpleParallax',
						SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/simpleparallax' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
						array( 'jquery', 'siteorigin-setup-parallax' ),
						'5.5.1'
					);

					wp_localize_script(
						'simpleParallax',
						'parallaxStyles',
						apply_filters(
							'siteorigin_premium_parallax_fallback_settings',
							array(
								'mobile-breakpoint'       => '780px',
								'disable-parallax-mobile' => false,
								'delay'                   => 0.4,
								'scale'                   => 1.1,
							)
						)
					);
				}
			} elseif ( ! wp_script_is( 'siteorigin-parallax', 'registered' ) ) {
				wp_register_script(
					'siteorigin-parallax',
					SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/siteorigin-parallax' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
					array( 'jquery' ),
					SITEORIGIN_PREMIUM_VERSION
				);
			}
		}

		public static function use_new_parallax() {
			$parallax_status = true;

			// If Page Builder is active, use the parallax Type setting value as the Parallax status.
			if ( function_exists( 'siteorigin_panels_setting' ) && ! empty( siteorigin_panels_setting( 'parallax-type' ) ) ) {
				$parallax_status = siteorigin_panels_setting( 'parallax-type' ) == 'modern';
			}

			return apply_filters( 'siteorigin_premium_parallax_type', $parallax_status );
		}

		/**
		 * Get a form instance.
		 *
		 * @return SiteOrigin_Premium_Form
		 */
		public function get_form( $name_prefix, $form_options ) {
			return new SiteOrigin_Premium_Form(
				$name_prefix,
				$form_options
			);
		}

		/**
		 * @return $links
		 */
		public function add_action_links( $links ) {
			unset( $links['edit'] );
			$links['addons'] = '<a href="' . esc_url( admin_url( 'admin.php?page=siteorigin-premium-addons' ) ) . '">' . esc_html__( 'Addons', 'siteorigin-premium' ) . '</a>';
			$links['license'] = '<a href="' . esc_url( admin_url( 'admin.php?page=siteorigin-premium-license' ) ) . '">' . esc_html__( 'License', 'siteorigin-premium' ) . '</a>';

			return $links;
		}

		/**
		 * Check if there are any registered metabox fields before loading it.
		 */
		public function load_metabox() {
			if ( is_admin() && class_exists( 'SiteOrigin_Widgets_Bundle' ) && ! empty( apply_filters( 'siteorigin_premium_metabox_form_options', array() ) ) ) {
				SiteOrigin_Premium_Metabox::single();
			}
		}

		/**
		 * Check if SiteOrigin Premium is in a theme.
		 *
		 * @return string False if not in theme mode or template/stylesheet if in theme mode.
		 */
		public static function is_theme_mode() {
			static $theme_mode = null;

			if ( ! is_null( $theme_mode ) ) {
				return $theme_mode;
			}

			$theme_mode = false;

			if ( strpos( __FILE__, get_template_directory() ) === 0 ) {
				$theme_mode = 'template';
			} elseif ( strpos( __FILE__, get_stylesheet_directory() ) === 0 ) {
				$theme_mode = 'stylesheet';
			}

			return $theme_mode;
		}

		/**
		 * Get the directory URL of a file.
		 *
		 * @return string
		 */
		public static function dir_url( $filename = false ) {
			if ( $filename === false ) {
				$filename = __FILE__;
			}

			switch( self::is_theme_mode() ) {
				case 'template':
					$url = str_replace( get_template_directory(), get_template_directory_uri(), dirname( $filename ) );
					break;

				case 'stylesheet':
					$url = str_replace( get_template_directory(), get_template_directory_uri(), dirname( $filename ) );
					break;
				default:
					$url = plugin_dir_url( $filename );
					break;
			}

			$url = rtrim( $url, '/' ) . '/';

			return $url;
		}

		/**
		 * Get the directory path of a file.
		 *
		 * @return string
		 */
		public static function dir_path( $filename = false ) {
			if ( $filename === false ) {
				$filename = __FILE__;
			}

			switch( self::is_theme_mode() ) {
				case 'template':
				case 'stylesheet':
					$dir = dirname( $filename );
					break;
				default:
					$dir = plugin_dir_path( $filename );
					break;
			}

			$dir = rtrim( $dir, '/' ) . '/';

			return $dir;
		}

		public static function update_url() {
			$url = 'https://siteorigin.com/wp-content/plugins/siteorigin-components/edd-actions.php';

			// Used to help with debugging on our side.
			if ( isset( $_GET['debug-ua-remove'] ) ) {
				delete_option( 'siteorigin_premium_ua_debug' );
			} elseif ( isset( $_GET['debug-ua'] ) || get_option( 'siteorigin_premium_ua_debug', false ) ) {
				update_option( 'siteorigin_premium_ua_debug', true );
				$url = add_query_arg( 'so-debug', 'true', $url );
			}

			return $url;
		}
	}
}

SiteOrigin_Premium::single();
