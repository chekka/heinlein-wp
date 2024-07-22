<?php

namespace P4W\Plugin_Manager;

require_once( ABSPATH . '/wp-admin/includes/file.php' );
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

use RuntimeException;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_Theme;

class Api {
	const ROUTE_NAMESPACE = 'p4w/v1';
	const SERVER_ROUTE_NAMESPACE = 'pluginsforwp/v1';
	const OPTIONS_PREFIX = 'p4w';

	public static function getServerUrl() {
		return getenv( 'P4W_TEST_SERVER' ) ?: Plugin_Manager::SERVER_URL;
	}

	/**
	 * Register controllers and api with wordpress
	 */
	public static function run() {
		add_action( 'rest_api_init',
			function () {
				$controllers = [ My_Products_Controller::class, Settings_Controller::class ];
				foreach ( $controllers as $controller ) {
					$instance = new $controller;
					$instance->register_routes();
				}
			}
		);
	}
}

class My_Products_Controller extends WP_REST_Controller {
	public function register_routes() {
		register_rest_route(
			Api::ROUTE_NAMESPACE,
			'/products/install',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'install' ],
					'permission_callback' => [ $this, 'install_permissions_check' ],
				],
			]
		);

		register_rest_route(
			Api::ROUTE_NAMESPACE,
			'/products/activate',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'activate' ],
					'permission_callback' => [ $this, 'activate_permissions_check' ],
				],
			]
		);

		register_rest_route(
			Api::ROUTE_NAMESPACE,
			'/products/list',
			[
				[
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_installed_products' ],
					'permission_callback' => [ $this, 'get_installed_products_permissions_check' ],
				],
			]
		);
	}

	/**
	 * Install action. Install a plugin
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_installed_products( $request ) {
		$plugins = get_plugins();
		foreach ( $plugins as &$plugin ) {
			$plugin['Name'] = $this->normalize_name( $plugin['Name'] );
		}

		$theme_objs = wp_get_themes( [ 'errors' => null ] );
		$themes     = [];
		foreach ( $theme_objs as $theme_obj ) {
			/** @var WP_Theme $theme_obj */
			$themes[] = [
				'Name'    => $this->normalize_name( $theme_obj->get( 'Name' ) ),
				'Version' => $theme_obj->get( 'Version' ),
			];
		}

		$serverUrl = Api::getServerUrl();
		$username  = get_option( Settings_Controller::PLUGINS_FOR_WP_USERNAME, null );
		if ( $username === '' ) {
			$username = null;
		}

		$key = get_option( Settings_Controller::PLUGINS_FOR_WP_SECRET_KEY, null );
		if ( $key === '' ) {
			$key = null;
		}

		$affiliate = get_option( Settings_Controller::PLUGINS_FOR_WP_AFFILIATE, null );
		if ( $affiliate === '' ) {
			$affiliate = null;
		}

		$data = [
			'plugins'   => $plugins,
			'themes'    => $themes,
			'serverUrl' => $serverUrl,
			'username'  => $username,
			'key'       => $key,
			'affiliate' => $affiliate,
		];

		return new WP_REST_Response( $data, 200 );
	}


	/**
	 * Normalize a plugin's name so it can be searched for easily in WP
	 * WP won't find the name with the dash on the left. It needs to be a regular - dash
	 *
	 * @param $name
	 *
	 * @return string|string[]
	 */
	public function normalize_name( $name ) {
		$norm_dashes = str_replace( '–', '-', $name );

		return html_entity_decode( $norm_dashes );
	}

	/**
	 * Install action. Install a plugin
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function install( $request ) {
		$my_product = \json_decode( $request->get_body(), true );
		$id         = $my_product['id'];

		$my_products     = $this->get_my_products( $id );
		$matched_product = isset( $my_products[0] ) ? $my_products[0] : null;
		if ( ! $matched_product ) {
			throw new RuntimeException( 'Product does not match' );
		}

		$this->install_product( $matched_product );

		return new WP_REST_Response( [], 200 );
	}

	/**
	 * Download and install a plugin or theme
	 *
	 * @param $product
	 */
	protected function install_product( $product ) {
		$plugins_path = null;
		if ( $product['type'] === 'plugin' ) {
			$plugins_path = ABSPATH . 'wp-content/plugins/';
			if ( defined( 'WP_PLUGIN_DIR' ) ) {
				$plugins_path = WP_PLUGIN_DIR . '/';
			}
		} elseif ( $product['type'] === 'theme' ) {
			$plugins_path = get_theme_root() . '/';
		}

		if ( ! $plugins_path ) {
			throw new RuntimeException( 'Plugin path not available' );
		}

		// Download file
		$ch = curl_init( $product['url'] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			throw new RuntimeException( 'File could not be downloaded' );
		}
		curl_close( $ch );

		// Unzip plugin
		$file = $plugins_path . $product['filename'];
		if ( file_put_contents( $file, $data ) ) {
			WP_Filesystem();
			$unzipfile = unzip_file( $file, $plugins_path );
			if ( ! $unzipfile ) {
				throw new RuntimeException( 'File could not be unzipped' );
			}

			unlink( $file );
		} else {
			throw new RuntimeException( 'File could not be downloaded' );
		}
	}

	/**
	 * Get my products list from the p4w server
	 *
	 * @param int|null $id
	 *
	 * @return array
	 */
	public function get_my_products( $id = null ) {
		$url = '/my-products';
		if ( $id ) {
			$url .= "?id=$id";
		}
		$response = $this->make_request( $url );

		if ( $response instanceof WP_Error ) {
			return [];
		}

		$code = $response['response']['code'] ? $response['response']['code'] : null;
		if ( $code !== 200 ) {
			return [];
		}

		$products = json_decode( $response['body'], true );
		if ( ! isset( $products['products'] ) ) {
			return [];
		}

		return $products['products'];
	}

	/**
	 * @param string $path
	 *
	 * @return array|WP_Error
	 */
	public function make_request( string $path ) {
		$username = get_option( Settings_Controller::PLUGINS_FOR_WP_USERNAME, null );
		if ( $username === '' ) {
			$username = null;
		}

		$secret = get_option( Settings_Controller::PLUGINS_FOR_WP_SECRET_KEY, null );
		if ( $secret === '' ) {
			$secret = null;
		}

		if ( ! $username || ! $secret ) {
			return null;
		}

		$url        = Api::getServerUrl();
		$url        .= '/wp-json/' . Api::SERVER_ROUTE_NAMESPACE . $path;
		$auth_token = base64_encode( $username . ':' . $secret );

		return wp_remote_request(
			$url,
			[
				'method'  => 'GET',
				'timeout' => 20,
				'headers' => [
					'Authorization' => "Basic $auth_token",
					'Content-type'  => 'application/json',
				],
			]
		);
	}

	/**
	 * Activate a plugin
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function activate( $request ) {
		$plugin = $request->get_params()['slug'];
		$result = activate_plugin( $plugin, '', is_network_admin() );
		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( 'There was an error installing this plugin.' );
		}

		if ( ! is_network_admin() ) {
			$recent = (array) get_option( 'recently_activated' );
			unset( $recent[ $plugin ] );
			update_option( 'recently_activated', $recent );
		} else {
			$recent = (array) get_site_option( 'recently_activated' );
			unset( $recent[ $plugin ] );
			update_site_option( 'recently_activated', $recent );
		}

		return new WP_REST_Response( [], 200 );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function install_permissions_check( $request ) {
		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function get_installed_products_permissions_check( $request ) {
		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function activate_permissions_check( $request ) {
		return true;
	}
}

class Settings_Controller extends WP_REST_Controller {
	const PLUGINS_FOR_WP_USERNAME = API::OPTIONS_PREFIX . '_username';
	const PLUGINS_FOR_WP_SECRET_KEY = API::OPTIONS_PREFIX . '_secret_key';
	const PLUGINS_FOR_WP_AFFILIATE = API::OPTIONS_PREFIX . '_affiliate';

	public function register_routes() {
		register_rest_route(
			Api::ROUTE_NAMESPACE,
			'/settings/save',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'save_settings' ],
					'permission_callback' => [ $this, 'save_settings_permissions_check' ],
				],
			]
		);

		register_rest_route(
			Api::ROUTE_NAMESPACE,
			'/settings/update-admin-banner-time',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'update_admin_banner_time' ],
					'permission_callback' => [ $this, 'update_admin_banner_time_check' ],
				],
			]
		);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function save_settings( $request ) {
		$settings = \json_decode( $request->get_body(), true );

		update_option( self::PLUGINS_FOR_WP_USERNAME, trim( $settings['username'] ) );
		update_option( self::PLUGINS_FOR_WP_SECRET_KEY, trim( $settings['key'] ) );
		update_option( self::PLUGINS_FOR_WP_AFFILIATE, trim( $settings['affiliate'] ) );

		return new WP_REST_Response( [], 200 );
	}

	/**
	 * @param $request
	 */
	public function update_admin_banner_time( $request ) {
		update_option( 'p4w_admin_banner', time() );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool|true
	 */
	public function save_settings_permissions_check( $request ) {
		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function update_admin_banner_time_check( $request ) {
		return true;
	}
}
