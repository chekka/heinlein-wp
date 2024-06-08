<?php

/**
 * Class SiteOrigin_Premium_Admin_Notices
 */
class SiteOrigin_Premium_Admin_Notices {
	private $notices = array();

	/**
	 * Create the instance of the Premium Admin notices
	 */
	public function __construct() {
		$this->notices = include SiteOrigin_Premium::dir_path( __FILE__ ) . 'notices.php';

		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
		add_action( 'wp_ajax_so_premium_dismiss', array( $this, 'dismiss_action' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function display_admin_notices() {
		$notices = $this->get_displayed_notices();

		if ( ! empty( $notices ) ) {
			$license_key = get_option( 'siteorigin_premium_key', '' );

			foreach ( $notices as $id => $message ) {
				$dismiss_url = wp_nonce_url( add_query_arg( array(
					'action' => 'so_premium_dismiss',
					'id' => $id,
				), admin_url( 'admin-ajax.php' ) ), 'so_premium_dismiss' );
				?>

				<div id="siteorigin-premium-notice" class="notice-warning settings-error notice">
					<?php if ( empty( $message ) ) { ?>
						<p>
							<strong>
								<?php

								$look_for = array(
									'%renew%',
									'%purchase%',
									'%license_activation%',
									'%manage_sites%',
								);

								$replace_with = array(
									esc_url( 'https://siteorigin.com/checkout/?edd_license_key=' . $license_key . '&download_id=23323' ),
									'https://siteorigin.com/downloads/premium/',
									esc_url( admin_url( 'admin.php?page=siteorigin-premium-license' ) ),
								);

								$payment_details = get_option( 'siteorigin_premium_details' );
								// It's possible for us not to have payment details onfile, so we need to have a fallback.
								if ( empty( $payment_details ) ) {
									$replace_with[] = 'https://siteorigin.com/dashboard/?dashboard_tab=order_history';
								} else {
									$replace_with[] = esc_url( 'https://siteorigin.com/dashboard/history/?action=manage_licenses&payment_id=' . $payment_details['payment_id'] . '&license_id=' . $payment_details['license_id'] );
								}

								// Does this notice have page speicfic notices?
								if ( is_array( $this->notices[ $id ] ) ) {
									$screen = get_current_screen();

									if ( isset( $this->notices[ $id ][ $screen->id ] ) ) {
										$notice = $this->notices[ $id ][ $screen->id ];
									} else {
										$notice = $this->notices[ $id ]['global'];
									}
								} else {
									$notice = $this->notices[ $id ];
								}

								echo str_replace(
									$look_for,
									$replace_with,
									$notice
								);
								?>
							</strong>
						</p>
						<p>
							<small><em><?php printf(
								__( 'If you think this is a mistake, please %scontact support%s.', 'siteorigin-premium' ),
								'<a href="mailto:support@siteorigin.com">',
								'</a>'
							);
							?></em></small>
						</p>
					<?php } else { ?>
						<p>
							<?php echo wp_kses_post( $message ); ?>
						</p>
					<?php } ?>
					<a href="<?php echo esc_url( $dismiss_url ); ?>" class="siteorigin-notice-dismiss"></a>
				</div>
				<?php
			}

			wp_enqueue_script( 'siteorigin-premium-notice', SiteOrigin_Premium::dir_url() . 'admin/js/notices' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js', array( 'jquery' ), SITEORIGIN_PREMIUM_VERSION );
			wp_enqueue_style( 'siteorigin-premium-notice', SiteOrigin_Premium::dir_url() . 'admin/css/notices.css' );
		}
	}

	/**
	 * Checks whether there is a notice available for the given status
	 *
	 * @param string $status The status of the Premium license.
	 *
	 * @return bool Whether there is a notice available for the given status.
	 */
	public function has_notice( $status ) {
		return isset( $this->notices[ $status ] );
	}

	/**
	 * Clears any active notices for Premium license statuses. Used to reset notices when a new license key is saved.
	 */
	public function clear_notices() {
		update_option( 'siteorigin_premium_active_notices', array() );
		update_option( 'siteorigin_premium_dismissed_notices', array() );
	}

	/**
	 * Activate a notice
	 */
	public function activate_notice( $id, $message = false ) {
		$active = get_option( 'siteorigin_premium_active_notices', array() );
		$active[$id] = $message;
		update_option( 'siteorigin_premium_active_notices', $active );
	}

	public function dismiss_action() {
		check_ajax_referer( 'so_premium_dismiss' );

		$dismissed = get_option( 'siteorigin_premium_dismissed_notices', array() );
		$id = sanitize_text_field( $_GET['id'] );
		$dismissed[$id] = array(
			'expires' => 365 * 86400 + time(),
		);
		update_option( 'siteorigin_premium_dismissed_notices', $dismissed );

		exit();
	}

	/**
	 * Get a list of notices that we should be displaying
	 *
	 * @return array
	 */
	public function get_displayed_notices() {
		$active = get_option( 'siteorigin_premium_active_notices', array() );
		$dismissed = get_option( 'siteorigin_premium_dismissed_notices', array() );

		foreach ( $dismissed as $id => $attr ) {
			if ( $attr['expires'] > 0 && $attr['expires'] < time() ) {
				unset( $dismissed[$id] );
				update_option( 'siteorigin_premium_dismissed_notices', $dismissed );
			} else {
				unset( $active[$id] );
			}
		}

		return $active;
	}
}
