<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SiteOrigin_Premium_License
 *
 * Encapsulates license related functionality. Makes any EDD license related calls to our API.
 */
class SiteOrigin_Premium_License {
	// 'valid'/'invalid' are returned by EDD and 'active'/'inactive' are set locally.
	const STATUS_VALID = 'valid';
	const STATUS_ACTIVE = 'active';
	const STATUS_INVALID = 'invalid';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_EXPIRED = 'expired';
	const STATUS_NO_ACTIVATION = 'no_activations_left';

	private $license_key = '';
	private $edd_actions;
	private $license_data = array();

	public function __construct( $license_key ) {
		$this->license_key = trim( $license_key );
		$this->edd_actions = new SiteOrigin_Premium_EDD_Actions();
	}

	public function get_license_data() {
		return $this->license_data;
	}

	/**
	 * Check whether the license key is valid and not expired.
	 *
	 * @return string The status
	 */
	public function check_license_key( $update_status = true, $debug = false ) {
		$this->license_data = $this->edd_actions->check_license( $this->license_key, $debug );

		if ( ! empty( $this->license_data ) ) {
			if ( $update_status && ! isset( $this->license_data->first ) ) {
				return $this->update_status( $this->license_data );
			} else {
				return $this->license_data;
			}
		}
	}

	/**
	 * Send a request to the SiteOrigin Premium servers to activate this license.
	 *
	 * @return string The status
	 */
	public function activate_license() {
		$this->license_data = $this->edd_actions->activate_license( $this->license_key );

		update_option( 'siteorigin_premium_key', $this->license_key );
		delete_option( 'siteorigin_premium_details' );

		// Clear any logged errors. This will allow for a new errors to be able to be logged.
		if ( ! empty( $license_data->license ) && $license_data->license == 'valid' ) {
			delete_option( 'siteorigin_premium_license_error' );
		}

		return $this->update_status( $this->license_data );
	}

	/**
	 * Send a request to the SiteOrigin Premium servers to deactivate this license.
	 *
	 * @return string The status
	 */
	public function deactivate_license() {
		$response = $this->edd_actions->deactivate_license( $this->license_key );

		if ( ! empty( $response ) && $response->success ) {
			delete_option( 'siteorigin_premium_license_status' );
			delete_option( 'siteorigin_premium_key' );
			delete_option( 'siteorigin_premium_details' );

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Do a database check to see if the license has been activated.
	 *
	 * @return bool
	 */
	public function is_active() {
		$status = get_option( 'siteorigin_premium_license_status' );
		$active_statuses = array( SiteOrigin_Premium_License::STATUS_ACTIVE, SiteOrigin_Premium_License::STATUS_VALID );

		return in_array( $status, $active_statuses ) && get_option( 'siteorigin_premium_key' ) == $this->license_key;
	}

	/**
	 * Check the response from EDD to see whether the license is valid and not expired and update the option in the DB.
	 *
	 * @param stdClass $this->license_data License data from the EDD endpoint
	 *
	 * @return string The status.
	 */
	private function update_status( $license_data = array() ) {
		if ( ! empty( $license_data ) ) {
			$this->license_data = $license_data;
		}

		if ( ! isset( $this->license_data->success ) ) {
			$status = SiteOrigin_Premium_License::STATUS_INACTIVE;
		} elseif ( ! isset( $this->license_data->license ) ) {
			$status = SiteOrigin_Premium_License::STATUS_INVALID;
		} else {
			$status = $this->license_data->license;
		}

		// Has the user activated too many sites?
		if (
			$status == SiteOrigin_Premium_License::STATUS_INVALID &&
			isset( $this->license_data->error ) &&
			$this->license_data->error == SiteOrigin_Premium_License::STATUS_NO_ACTIVATION
		) {
			$status = SiteOrigin_Premium_License::STATUS_NO_ACTIVATION;
		} elseif (
			// If status was returned by EDD as 'invalid' check if it has expired.
			$status == SiteOrigin_Premium_License::STATUS_EXPIRED ||
			(
				$status == SiteOrigin_Premium_License::STATUS_INVALID &&
				isset( $this->license_data->expires ) &&
				$this->license_data->expires != 'lifetime'
			)
		) {
			$license_expires = new DateTime( $this->license_data->expires );
			$now = new DateTime();

			// Has the license expired?
			if ( $license_expires < $now ) {
				$status = SiteOrigin_Premium_License::STATUS_EXPIRED;
			}
		}

		// If the license was previously set, the new status isn't active/valid, and
		// the new status isn't the same as the old status, log the request.
		$existing_status = get_option( 'siteorigin_premium_license_status' );
		$exclude_active_statuses = array( SiteOrigin_Premium_License::STATUS_ACTIVE, SiteOrigin_Premium_License::STATUS_VALID );

		if (
			! empty( $existing_status ) &&
			! in_array( $status, $exclude_active_statuses ) &&
			$status != $existing_status
		) {
			// Check if an error was previously logged, and if there is one, don't log.
			// We only want to log the first error as any more than that could override it with valid information.
			$license_error = get_option( 'siteorigin_premium_license_error' );

			if ( empty( $license_error ) ) {
				update_option( 'siteorigin_premium_license_error', $this->license_data );
			}
		}

		update_option( 'siteorigin_premium_license_status', $status );

		return $status;
	}
}
