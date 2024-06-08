<?php

class SiteOrigin_Premium_Options {
	private $messages;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_page' ), 9 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'current_screen', array( $this, 'save_premium_license' ) );

		add_action( 'wp_ajax_so_premium_change_status', array( $this, 'change_status_action' ) );

		add_action( 'wp_ajax_so_premium_addon_settings_form', array( $this, 'get_addon_settings_form' ) );
		add_action( 'wp_ajax_so_premium_addon_settings_save', array( $this, 'addon_settings_save' ) );

		$this->messages = array();
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Add the options page.
	 */
	public function add_admin_page() {
		if ( empty( $GLOBALS['admin_page_hooks']['siteorigin'] ) ) {
			add_menu_page(
				__( 'SiteOrigin', 'siteorigin-premium' ),
				__( 'SiteOrigin', 'siteorigin-premium' ),
				'manage_options',
				'siteorigin',
				'',
				SiteOrigin_Premium::dir_url( __FILE__ ) . '../img/menu-icon.svg',
				66
			);
		}

		add_submenu_page(
			'siteorigin',
			__( 'Premium Addons', 'siteorigin-premium' ),
			__( 'Premium Addons', 'siteorigin-premium' ),
			'manage_options',
			'siteorigin-premium-addons',
			array( $this, 'render_addons_page' )
		);

		add_submenu_page(
			'siteorigin',
			__( 'Premium License', 'siteorigin-premium' ),
			__( 'Premium License', 'siteorigin-premium' ),
			'manage_options',
			'siteorigin-premium-license',
			array( $this, 'render_license_page' )
		);

		// Prevent empty "SiteOrigin" menu item by removing main menu page.
		// This has to be done after submenu items are added, or the entire menu will disappear.
		remove_submenu_page( 'siteorigin', 'siteorigin' );
	}

	/**
	 * Enqueue the admin scripts for the premium settings page
	 */

	function enqueue_admin_scripts( $prefix ) {
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( $current_screen && method_exists( $current_screen, 'is_block_editor' ) ) {
				if ( $current_screen->is_block_editor() ) {
					wp_enqueue_style( 'siteorigin-premium-block-editor', SiteOrigin_Premium::dir_url( __FILE__ ) . 'css/block-editor.css' );
				}
			}
		}

		$prefix = strtolower( $prefix );

		wp_enqueue_script( 'siteorigin-premium-trunk-animation', SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/trunk-animation' . SiteOrigin_Premium::$js_suffix . '.js', array( 'jquery' ), SITEORIGIN_PREMIUM_VERSION );

		if ( $prefix == 'siteorigin_page_siteorigin-premium-license' || $prefix == 'siteorigin_page_siteorigin-premium-addons' ) {
			wp_enqueue_style( 'siteorigin-premium-admin', SiteOrigin_Premium::dir_url( __FILE__ ) . 'css/admin.css', array(), SITEORIGIN_PREMIUM_VERSION );
		}

		if ( $prefix == 'siteorigin_page_siteorigin-premium-addons' ) {
			wp_enqueue_script( 'vimeo', 'https://player.vimeo.com/api/player.js', array(), '2.7.0' );
			wp_enqueue_script( 'siteorigin-premium-trianglify', SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/trianglify' . SiteOrigin_Premium::$js_suffix . '.js', array( 'jquery' ), SITEORIGIN_PREMIUM_VERSION );
			wp_enqueue_script( 'siteorigin-premium-addons', SiteOrigin_Premium::dir_url( __FILE__ ) . 'js/addons' . SiteOrigin_Premium::$js_suffix . '.js', array( 'jquery' ), SITEORIGIN_PREMIUM_VERSION );

			wp_localize_script(
				'siteorigin-premium-addons',
				'soPremiumAddons',
				array(
					'settingsForm' => array(
						'error' => __( 'Error loading form! Please try again later.', 'siteorigin-premium' ),
					),
				)
			);

			if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
				SiteOrigin_Panels_Admin::single()->enqueue_admin_scripts( null, true );
				SiteOrigin_Panels_Admin::single()->enqueue_admin_styles( null, true );
			}
		}
	}

	/**
	 * Save the premium license.
	 */
	public function save_premium_license() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		if ( empty( $_POST ) ) {
			return;
		}

		$current_screen = get_current_screen();
		if (
			! is_object( $current_screen ) ||
			! isset( $current_screen->id ) ||
			strtolower( $current_screen->id ) != 'siteorigin_page_siteorigin-premium-license'
		) {
			return;
		}

		if (
			! check_admin_referer( 'save_siteorigin_premium' ) ||
			! current_user_can( 'manage_options' )
		) {
			return;
		}

		if ( isset( $_GET['sow-debug-license'] ) ) {
			$die = false;
			$license_error = get_option( 'siteorigin_premium_license_error' );

			if ( ! empty( $license_error ) ) {
				echo '<pre>';
				echo __( 'License Status when last error occurred:', 'siteorigin-premium' ) . '<br>';
				print_r( $license_error );
				echo '</pre>';
				$die = true;
			}

			$license_key = get_option( 'siteorigin_premium_key' );

			if ( ! empty( $license_key ) ) {
				$license = new SiteOrigin_Premium_License( $license_key );
				$license_data = $license->check_license_key( false, true );
				$response = $license_data->response;
				unset( $license_data->request );
				echo '<pre>';

				if ( ! empty( $license_data ) ) {
					if ( empty( $license_data->errors ) ) {
						echo __( 'No connection issues detected during this test.', 'siteorigin-premium' );
						echo '<br>';
					}
					echo __( 'License Status:', 'siteorigin-premium' ) . '<br>';
					print_r( $license_data );
				} else {
					echo __( 'Request to SiteOrigin.com failed.', 'siteorigin-premium' ) . '<br>';
				}
				echo __( 'SiteOrigin.com response:', 'siteorigin-premium' ) . '<br>';
				print_r( $response );
				echo '</pre>';
				$die = true;
			}

			if ( $die ) {
				die();
			}
		}

		// Save the settings.
		$settings_raw = ! empty( $_POST['siteorigin_premium'] ) ? $_POST['siteorigin_premium'] : array();
		$license_key = ! empty( $settings_raw['key'] ) ? sanitize_text_field( $settings_raw['key'] ) : '';
		$notices = SiteOrigin_Premium_Admin_Notices::single();
		$notices->clear_notices();

		if ( empty( $license_key ) ) {
			$key = get_option( 'siteorigin_premium_key', '' );

			if ( ! empty( $key ) ) {
				$license = new SiteOrigin_Premium_License( $key );
			}

			// Check if a license is active. If there is one,
			// deactivate it before clearing it.
			if ( ! empty( $license ) && $license->is_active() ) {
				$license->deactivate_license();
				$this->messages[] = array(
					'type' => 'updated',
					'message' => __( 'The license key field has been cleared and your license has been deactivated.', 'siteorigin-premium' ),
				);
			} else {
				delete_option( 'siteorigin_premium_license_status' );
				delete_option( 'siteorigin_premium_key' );
				delete_option( 'siteorigin_premium_details' );
				$this->messages[] = array(
					'type' => 'updated',
					'message' => __( 'The license key field has been cleared.', 'siteorigin-premium' ),
				);
			}
		} else {
			$license = new SiteOrigin_Premium_License( $license_key );
			$activate_status = $license->activate_license();

			if (
				in_array(
					$activate_status,
					array(
						SiteOrigin_Premium_License::STATUS_INACTIVE,
						SiteOrigin_Premium_License::STATUS_INVALID,
						SiteOrigin_Premium_License::STATUS_EXPIRED,
						SiteOrigin_Premium_License::STATUS_NO_ACTIVATION
					)
				)
			) {
				$License_data = $license->get_license_data();
				// If $payment_id is set, store it.
				if ( ! empty( $License_data->payment_id ) ) {
					update_option( 'siteorigin_premium_details', array(
						'payment_id' => $License_data->payment_id,
						'license_id' => $License_data->license_id,
					) );
				}
				$notices->activate_notice( $activate_status );
			} else {
				$this->messages[] = array(
					'type' => 'updated',
					'message' => __( 'Your license has been activated.', 'siteorigin-premium' ),
				);
			}
		}
	}

	/**
	 * Render the options page.
	 */
	public function render_license_page() {
		$key = get_option( 'siteorigin_premium_key', '' );
		$license = new SiteOrigin_Premium_License( $key );
		$license_active = $license->is_active();

		// Don't show the full license key.
		if ( ! empty( $key ) && strlen( $key ) > 15 ) {
			$partial_key = substr( $key, strlen( $key ) - 4 );
		}

		include SiteOrigin_Premium::dir_path( __FILE__ ) . 'tpl/license-page.php';
	}

	public function render_addons_page() {
		// Include all the addons.
		$addons = array(
			'plugin' => array(),
			'theme' => array(),
		);

		$filter = empty( $_GET['filter'] ) ? '' : $_GET['filter'];

		$default_headers = array(
			'Name' => 'Plugin Name',
			'Description' => 'Description',
			'Documentation' => 'Documentation',
			'Tags' => 'Tags',
			'Video' => 'Video',
			'minimum' => 'Minimum Version',
		);

		$so_plugins = array(
			'siteorigin-panels' => array(
				'name' => __( 'SiteOrigin Page Builder', 'siteorigin-premium' ),
				'version' => 'SITEORIGIN_PANELS_VERSION'
			),
			'so-widgets-bundle' => array(
				'name' => __( 'SiteOrigin Widgets Bundle', 'siteorigin-premium' ),
				'version' => 'SOW_BUNDLE_VERSION'
			),
			'so-css' => array(
				'name' => __( 'SiteOrigin CSS', 'siteorigin-premium' ),
				'version' => 'SO_CSS_VERSION'
			),
		);

		foreach ( $addons as $section => $section_addons ) {
			$folder = SiteOrigin_Premium::dir_path( __FILE__ ) . '../addons/' . $section . '/';

			foreach ( glob( $folder . '*/*.php' ) as $filename ) {
				$p = pathinfo( $filename );
				$addon_id = $section . '/' . $p['filename'];
				$theme_support_id = $p['filename'];

				if ( $section == 'theme' && ! current_theme_supports( 'siteorigin-premium-' . $theme_support_id ) ) {
					// These theme doesn't support this feature.
					continue;
				}

				$data = get_file_data( $filename, $default_headers );

				// Need to explicitly call `translate` for file headers.
				foreach ( array( 'Name', 'Description', 'Tags' ) as $field ) {
					$data[ $field ] = translate( $data[ $field ], 'siteorigin-premium' );
				}

				$data['ID'] = $addon_id;
				$data['File'] = $filename;
				$data['Type'] = 'plugin';

				// Remove SiteOrigin from the start of the addon name.
				$data['Name'] = str_replace( 'SiteOrigin ', '', $data['Name'] );

				if ( $section == 'plugin' ) {
					$data['CanEnable'] = true;
					$data['Active'] = SiteOrigin_Premium::single()->is_addon_active( $addon_id );

					// Check if this addon has a minimum required version set.
					if ( ! empty( $data['minimum'] ) ) {
						list( $required_plugin, $required_version ) = explode( ' ', $data['minimum'] );

						// Check for required plugin details. If found, attempt to get installed version.
						if ( isset( $so_plugins[ $required_plugin ] ) ) {
							$required_plugin = $so_plugins[ $required_plugin ];
							if ( defined( $required_plugin['version'] ) ) {
								$installed_version = constant( $required_plugin['version'] );
							}
						}

						if ( empty( $installed_version ) ) {
							// Prevent activation as the required plugin isn't installed.
							$data['Active'] = false;
							$data['CanEnable'] = false;
							$data['Description'] .= '<br><br>' . sprintf(
								__( 'The %s Addon requires the installation of %s.', 'siteorigin-premium' ),
								$data['Name'],
								$required_plugin['name']
							);

						} elseif (
							$installed_version != 'dev' &&
							version_compare( $required_version, $installed_version, '>=' )
						) {
							// Prevent activation as the required plugin doesn't meet the minimum version.
							$data['Active'] = false;
							$data['CanEnable'] = false;
							$data['Description'] .= '<br><br>' . sprintf(
								__( 'The %s Addon requires %s %s or higher.', 'siteorigin-premium' ),
								$data['Name'],
								$required_plugin['name'],
								$required_version
							);
						}
					}

				} else {
					$theme_supports = get_theme_support( 'siteorigin-premium-' . $theme_support_id );

					if ( ! empty( $theme_supports ) ) {
						if ( is_array( $theme_supports ) ) {
							$support = current( get_theme_support( 'siteorigin-premium-' . $theme_support_id ) );
							$data['Active'] = ! empty( $support['enabled'] );

							// We can enable/disable this addon if the theme mod is known.
							if ( ! empty( $support['theme_mod'] ) || ! empty( $support['siteorigin_setting'] ) ) {
								$data['CanEnable'] = true;
							}
						} else {
							$data['CanEnable'] = true;
							$data['Active'] = SiteOrigin_Premium::single()->is_addon_active( $addon_id );
						}
					} else {
						$data['Active'] = false;
						$data['CanEnable'] = false;
					}
				}

				if ( $data['CanEnable'] ) {
					/** @var SiteOrigin_Premium_Form $settings_form */
					$settings_form = null;
					$addon = SiteOrigin_Premium::single()->load_addon( $addon_id );

					if ( ! empty( $addon ) && method_exists( $addon, 'get_settings_form' ) ) {
						$settings_form = $addon->get_settings_form();
					}

					if ( ! empty( $settings_form ) ) {
						$form_url = add_query_arg(
							array(
								'id'     => $addon_id,
								'action' => 'so_premium_addon_settings_form',
							),
							admin_url( 'admin-ajax.php' )
						);
						$data['form_url'] = esc_url( wp_nonce_url( $form_url, 'display-addon-settings-form' ) );

						// Enqueue scripts and styles for the form fields.
						ob_start();
						$settings_form->form( array() );
						ob_get_clean();
					}
					$data['has_settings'] = ! empty( $settings_form );
				}

				$addons[$section][$addon_id] = apply_filters( 'siteorigin_premium_addon_data-'. $addon_id, $data );
			}
		}

		do_action( 'siteorigin_premium_addons_page_scripts' );

		$action_url = add_query_arg( array(
			'action' => 'so_premium_change_status',
		), admin_url( 'admin-ajax.php' ) );

		$action_url = wp_nonce_url( $action_url, 'change_status' );

		include SiteOrigin_Premium::dir_path( __FILE__ ) . 'tpl/addons-page.php';
	}

	public function get_addon_settings_form() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'display-addon-settings-form' ) ) {
			exit();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			exit();
		}

		$addon_id = empty( $_GET['id'] ) ? false : $_GET['id'];

		if ( ! SiteOrigin_Premium::single()->is_addon_active( $addon_id ) ) {
			exit();
		}

		$action_url = admin_url( 'admin-ajax.php' );
		$action_url = add_query_arg( array(
			'id' => $addon_id,
			'action' => 'so_premium_addon_settings_save',
		), $action_url );
		$action_url = wp_nonce_url( $action_url, 'save-premium-addon-settings' );

		$value = $this->get_settings( $addon_id );

		/** @var SiteOrigin_Premium_Form $settings_form */
		$settings_form = null;
		$addon = SiteOrigin_Premium::single()->load_addon( $addon_id );

		if ( ! empty( $addon ) && method_exists( $addon, 'get_settings_form' ) ) {
			$settings_form = $addon->get_settings_form();
			?>
			<form method="post" action="<?php echo esc_url( $action_url ); ?>" target="so-premium-addon-settings-save">
				<?php $settings_form->form( $value ); ?>
			</form>
			<?php
		}

		exit();
	}

	public function addon_settings_save() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'save-premium-addon-settings' ) ) {
			exit();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			exit();
		}

		$addon_id = empty( $_GET['id'] ) ? false : $_GET['id'];
		$new_settings = array_values( $_POST );
		$this->save_settings( $addon_id, $new_settings, true );
		die();
	}

	public function save_settings( $addon_id, $new_settings, $exit_if_no_addon = false ) {
		$addon = SiteOrigin_Premium::single()->load_addon( $addon_id );
		if ( empty( $addon ) || ! method_exists( $addon, 'get_settings_form' ) ) {
			if ( $exit_if_no_addon ) {
				exit();
			}
			return;
		}
		/** @var SiteOrigin_Premium_Form $settings_form */
		$settings_form = $addon->get_settings_form();

		$new_settings = array_values( $_POST );
		$old_settings = $this->get_settings( $addon_id );

		$new_settings = $settings_form->update( stripslashes_deep( array_shift( $new_settings ) ), $old_settings );

		unset( $new_settings['_sow_form_id'] );
		unset( $new_settings['_sow_form_timestamp'] );
		update_option( 'so_premium_addon_settings[' . $addon_id . ']', $new_settings );
	}

	public function get_settings( $addon_id, $load_defaults = true ) {
		$values = get_option( 'so_premium_addon_settings[' . $addon_id . ']', array() );

		if ( $load_defaults ) {
			/** @var SiteOrigin_Premium_Form $settings_form */
			$settings_form = null;
			$addon = SiteOrigin_Premium::single()->load_addon( $addon_id );

			if ( ! empty( $addon ) && method_exists( $addon, 'get_settings_form' ) ) {
				$settings_form = $addon->get_settings_form();

				if ( method_exists( $settings_form, 'add_defaults' ) ) {
					// Add in the defaults.
					$values = $settings_form->add_defaults( $settings_form->form_options(), $values );
				}
			}
		}

		return $values;
	}

	public function display_key_message( $license_active ) {
		if ( ! $license_active ) {
			$this->messages[] = array(
				'type' => 'error',
				'message' => __( "You're using SiteOrigin Premium in development mode. Add and activate your license key to change to production mode.", 'siteorigin-premium' ) .
					' ' .
					__( "Development mode uses slower raw files, but they're ideal when you're still developing a site.", 'siteorigin-premium' ),
			);
		}

		if ( empty( $this->messages ) ) {
			return;
		}

		foreach ( $this->messages as $message ) {
			?>
			<div class="<?php echo esc_attr( $message['type'] ); ?>">
				<p>
					<?php echo esc_html( $message['message'] ); ?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Change the status of an addon.
	 */
	public function change_status_action() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'change_status' ) ) {
			exit();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			exit();
		}

		if (
			! isset( $_POST['id'] ) ||
			! isset( $_POST['section'] ) ||
			! isset( $_POST['status'] )
		) {
			exit();
		}

		$id = stripslashes( $_POST['id'] );
		$section = stripslashes( $_POST['section'] );
		$status = (bool) stripslashes( $_POST['status'] );

		list( $addon_section, $addon_id ) = explode( '/', $id, 2 );

		// The section should be the same from both places.
		if ( $section !== $addon_section ) {
			exit();
		}

		$addon_id = sanitize_file_name( $addon_id );

		if ( $section == 'theme' ) {
			// This needs to be changed via the theme mod.

			$support = get_theme_support( 'siteorigin-premium-' . $addon_id );
			if ( is_array( $support ) ) {
				$support = current( $support );

				if ( ! empty( $support['theme_mod'] ) ) {
					$theme_mod = $support['theme_mod'];
					if ( $theme_mod[0] == '!' ) {
						// The ! means we want the mod to be the opposite.
						set_theme_mod( substr( $theme_mod, 1 ), ! $status );
					} else {
						set_theme_mod( $theme_mod, $status );
					}
				} elseif ( ! empty( $support['siteorigin_setting'] ) || class_exists( 'SiteOrigin_Settings' ) ) {
					$setting_key = $support['siteorigin_setting'];
					$settings = SiteOrigin_Settings::single();
					if ( $setting_key[0] == '!' ) {
						// The ! means we want the mod to be the opposite.
						$settings->set( substr( $setting_key, 1 ), ! $status );
					} else {
						$settings->set( $setting_key, $status );
					}
				}
			} else {
				SiteOrigin_Premium::single()->set_addon_active( $id, $status );
			}
		} elseif ( $section == 'plugin' ) {
			SiteOrigin_Premium::single()->set_addon_active( $id, $status );

			// Check if we need to activate the required plugins/widgets.
			if ( $status && ! empty( $_POST['activate_required'] ) ) {
				$data = get_file_data(
					SiteOrigin_Premium::dir_path( __FILE__ ) . '../addons/' . $addon_section . '/' . $addon_id . '/' . $addon_id . '.php',
					array(
						'Requires' => 'Requires',
					)
				);

				if ( ! empty( $data['Requires'] ) ) {
					$requires = explode( ',', $data['Requires'] );

					foreach ( array_map( 'trim', $requires ) as $r ) {
						@ list( $plugin, $widget ) = explode( '/', $r );

						if ( ! is_plugin_active( $plugin . '/' . $plugin . '.php' ) && current_user_can( 'activate_plugins' ) ) {
							activate_plugin( $plugin . '/' . $plugin . '.php' );
						}

						if (
							$plugin == 'so-widgets-bundle' &&
							! empty( $widget ) &&
							current_user_can( apply_filters( 'siteorigin_widgets_admin_menu_capability', 'manage_options' ) ) &&
							class_exists( 'SiteOrigin_Widgets_Bundle' )
						) {
							SiteOrigin_Widgets_Bundle::single()->activate_widget( $widget );
						}
					}
				}
			}
		} else {
			// Not a valid section. Abort.
			die();
		}

		header( 'content-type: application/json' );

		SiteOrigin_Premium::single()->load_addon( $addon_section . '/' . $addon_id );
		$submenu_links = apply_filters( 'siteorigin_premium_addon_submenu_links-' . $addon_section . '/' . $addon_id, array() );

		if ( $status ) {
			// This has been activated.
			$action_links = apply_filters( 'siteorigin_premium_addon_action_links-' . $addon_section . '/' . $addon_id, array() );

			echo wp_json_encode( array(
				'status' => 'enabled',
				'action_links' => array_values( $action_links ),
				'submenu_links' => array_values( $submenu_links ),
			) );
		} else {
			// This has been deactivated.
			echo wp_json_encode( array(
				'status' => 'disabled',
				'submenu_links' => array_values( $submenu_links ),
			) );
		}

		exit();
	}
}

SiteOrigin_Premium_Options::single();
