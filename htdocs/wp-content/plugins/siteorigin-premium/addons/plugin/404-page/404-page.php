<?php
/*
Plugin Name: SiteOrigin 404 Page
Description: Create custom 404 error pages with personalized design and content. Guide your user's website experience even during misdirections.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/404-page/
Tags: Page Builder, Widgets Bundle
*/

class SiteOrigin_Premium_Plugin_404_Page extends SiteOrigin_Premium_Central_Gate {
	public function __construct() {
		if ( is_admin() ) {
			add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ), 1 );
			add_filter( 'display_post_states', array( $this, 'add_404_page_state' ), 10, 2 );
		} else {
			add_action( 'template_redirect', array( $this, 'show_404_page' ) );
		}
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function get_settings_form() {
		// Load defaults.
		$form_options = $this->form_options( true );

		// Add addon specific values.
		$form_options['enabled']['label'] = __( 'Enable Custom 404 Page', 'siteorigin-premium' );
		$form_options['title']['default'] = $this->default_title();
		$form_options['content_text']['default'] = __(
			"<h1>Page Not Found</h1>
			<p>Oops! The page you're looking for seems to have disappeared or doesn't exist. Don't worry, it's not you, it's us.</p>
			<p><strong>Here's what you can do:</strong></p>
			<ul>
				<li>Go Back: Use your browser's back button to return to the previous page.</li>
				<li>Home Sweet Home: Visit our <a href=''>Home page</a> to start fresh.</li>
				<li>Help: Need assistance? Our <a href=''>Support Team</a> is here to help you.</li>
			</ul>
			<p>We're sorry for any inconvenience. Sometimes links get broken or pages get moved. But there's plenty more to explore. Let's get you back to finding what you were looking for.</p>",
			'siteorigin-premium'
		);

		return new SiteOrigin_Premium_Form(
			'so-addon-404-page-settings',
			$form_options
		);
	}

	public function default_title() {
		return sprintf( __( '404 Not Found - %s', 'siteorigin-premium' ), get_bloginfo( 'name' ) );
	}

	public function metabox_options( $form_options ) {
		global $post;

		$this->setup_settings( 'plugin/404-page' );

		if (
			! isset( $post ) ||
			! $post instanceof WP_Post
		) {
			return $form_options;
		}

		// Check if the current page is assigned as the 404 page or not.
		if ( $this->get_page_id() !== $post->ID ) {
			return $form_options;
		}
		$form_options['general']['fields']['404_notice'] = array(
			'type' => 'html',
			'markup' => __( "You're editing the assigned 404 error page.", 'siteorigin-premium' ),
		);

		return $form_options;
	}

	public function add_404_page_state( $post_states, $post ) {
		$this->setup_settings( 'plugin/404-page' );

		if ( $this->get_page_id() === $post->ID ) {
			$post_states['siteorigin_404_page'] = __( 'SiteOrigin 404 Page', 'siteorigin-premium' );
		}

		return $post_states;
	}

	public function show_404_page() {
		if ( ! is_404() || ! apply_filters( 'siteorigin_premium_404_page_show', true ) ) {
			return;
		}

		$this->setup_settings( 'plugin/404-page' );
		$this->render();
	}
}
