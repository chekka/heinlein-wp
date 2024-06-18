<?php
/*
Plugin Name: Cloudflare Turnstile
Description: Enhance contact form security with Cloudflare Turnstile, a user-friendly CAPTCHA alternative that helps prevent spam while maintaining seamless user interaction.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/cloudflare-turnstile/
Tags: Widgets Bundle
Video:
Requires: so-widgets-bundle/contact
Minimum Version: so-widgets-bundle 1.58.11
*/

class SiteOrigin_Premium_Plugin_CF_Turnstile {
	public function __construct() {
		add_action( 'init', array( $this, 'init_addon' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init_addon() {
		if ( ! class_exists( 'SiteOrigin_Widgets_ContactForm_Widget' ) ) {
			return;
		}

		add_filter( 'siteorigin_widgets_form_options_sow-contact-form', array( $this, 'admin_form_options' ), 9 );
		add_filter( 'siteorigin_widgets_contact_spam_check', array( $this, 'spam_check' ), 10, 4 );

		add_action( 'siteorigin_widgets_contact_before_submit', array( $this, 'add_cf_turnstile' ) );
	}

	public function admin_form_options( $form_options ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		siteorigin_widgets_array_insert(
			$form_options['spam']['fields'],
			'akismet',
			array(
				'cf_turnstile' => array(
					'type' => 'section',
					'label' => __( 'Cloudflare Turnstile', 'siteorigin-premium' ),
					'hide' => true,
					'fields' => array(
						'enabled' => array(
							'type' => 'checkbox',
							'label' => __( 'Cloudflare Turnstile', 'siteorigin-premium' ),
							'description' => sprintf(
								__( "%sCloudflare Turnstile%s is a free CAPTCHA replacement, and it's reported to be CCPA, GDPR compliant.", 'siteorigin-premium' ),
								'<a href="https://www.cloudflare.com/products/turnstile/" target="_blank" rel="noopener noreferrer">',
								'</a>'
							),
							'state_emitter' => array(
								'callback' => 'conditional',
								'args' => array(
									'cf_turnstile[show]: val',
									'cf_turnstile[hide]: ! val',
								),
							),
						),
						'site_key' => array(
							'type' => 'text',
							'label' => __( 'Site Key', 'siteorigin-premium' ),
							'state_handler' => array(
								'cf_turnstile[show]' => array( 'show' ),
								'cf_turnstile[hide]' => array( 'hide' ),
							),
						),
						'secret_key' => array(
							'type' => 'text',
							'label' => __( 'Secret Key', 'siteorigin-premium' ),
							'state_handler' => array(
								'cf_turnstile[show]' => array( 'show' ),
								'cf_turnstile[hide]' => array( 'hide' ),
							),
						),
					),
				),
			)
		);

		return $form_options;
	}

	public function is_sf_turnstile_enabled( $settings ) {
		return ! (
			empty( $settings ) ||
			empty( $settings['spam'] ) ||
			empty( $settings['spam']['cf_turnstile'] ) ||
			empty( $settings['spam']['cf_turnstile']['enabled'] ) ||
			empty( $settings['spam']['cf_turnstile']['site_key'] ) ||
			empty( $settings['spam']['cf_turnstile']['secret_key'] )
		);
	}

	public function add_cf_turnstile( $instance ) {
		if ( ! $this->is_sf_turnstile_enabled( $instance ) ) {
			return;
		}
		?>
		<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

		<div class="cf-turnstile" data-sitekey="<?php
		echo esc_attr( $instance['spam']['cf_turnstile']['site_key'] );
		?>"></div>
		<?php
	}

	public function spam_check( $errors, $post_vars, $email_fields, $instance ) {
		if (
			! empty( $errors ) ||
			! $this->is_sf_turnstile_enabled( $instance )
		) {
			return $errors;
		}

		if ( empty( $post_vars['cf-turnstile-response'] ) ) {
			$errors['cf_turnstile'] = __( 'Error validating your response. Please try again.', 'siteorigin-premium' );

			return $errors;
		}

		$result = wp_remote_post(
			'https://challenges.cloudflare.com/turnstile/v0/siteverify',
			array(
				'body' => array(
					'secret' => $instance['spam']['cf_turnstile']['secret_key'],
					'response' => sanitize_text_field( $post_vars['cf-turnstile-response'] ),
				),
			)
		);

		if ( ! is_wp_error( $result ) && ! empty( $result['body'] ) ) {
			$result = json_decode( $result['body'], true );

			if ( isset( $result['success'] ) && ! $result['success'] ) {
				$errors['cf_turnstile'] = __( 'Error validating your response. Please try again.', 'siteorigin-premium' );
			}
		}

		return $errors;
	}
}
