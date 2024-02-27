<?php

if ( class_exists( 'SiteOrigin_Widget' ) ) {
	/**
	 * A form builder based on the SiteOrigin widgets bundle form base. This is not used as an actual widget.
	 *
	 * Class SiteOrigin_Premium_Form
	 */
	class SiteOrigin_Premium_Form extends SiteOrigin_Widget {
		private $name_prefix;
		// This is to ensure the name fields takes the form expected by Widgets Bundle's admin.js.
		private $fake_id = 'c00';
		private $modify_callback;

		/**
		 * @param string        $name_prefix
		 * @param string        $form_options
		 * @param bool|callable $modify_callback
		 */
		public function __construct( $name_prefix, $form_options, $modify_callback = false ) {
			parent::__construct(
				'siteorigin-premium-form',
				__( 'SiteOrigin Premium Form', 'siteorigin-premium' ),
				array(
					'has_preview' => false,
				),
				array(),
				$form_options,
				SiteOrigin_Premium::dir_path( __FILE__ )
			);

			$this->name_prefix = $name_prefix;
			$this->modify_callback = $modify_callback;

			static $form_number = 1;
			$this->number = $form_number++;
		}

		/**
		 * Get a specially prefixed name
		 *
		 * @param string $field_name
		 *
		 * @return string
		 */
		public function get_field_name( $field_name ) {
			return $this->name_prefix . '[' . $this->fake_id . '][' . $field_name . ']';
		}

		/**
		 * Modify the form instance using $modify_callback arg from the constructor.
		 *
		 * @return mixed
		 */
		public function modify_form( $form_options ) {
			if ( ! empty( $this->modify_callback ) ) {
				$form_options = call_user_func( $this->modify_callback, $form_options );
			}

			return $form_options;
		}

		/**
		 * Extract the new instances values before updating.
		 *
		 * @param string $form_type
		 *
		 * @return mixed
		 */
		public function update( $new_instance, $old_instance, $form_type = 'widget' ) {
			if ( ! empty( $new_instance[ $this->fake_id ] ) ) {
				$new_instance = $new_instance[ $this->fake_id ];
			}

			return parent::update( $new_instance, $old_instance, $form_type );
		}

		/**
		 * Chance the message displayed while loading the form.
		 */
		public function scripts_loading_message() {
			?>
			<p><strong><?php _e( 'Scripts and styles for this form are loading.', 'siteorigin-premium' ); ?></strong></p>
			<?php
		}

		/**
		 * This widget will never be rendered on the frontend, so add a noop.
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
		}

		protected function get_version() {
			return defined( 'SITEORIGIN_PREMIUM_VERSION' ) ? SITEORIGIN_PREMIUM_VERSION : 'dev';
		}

		protected function get_js_suffix() {
			return defined( 'SITEORIGIN_PREMIUM_JS_SUFFIX' ) ? SITEORIGIN_PREMIUM_JS_SUFFIX : '';
		}
	}
} else {
	class SiteOrigin_Premium_Form {
		public function __construct( $name_prefix, $form_options, $modify_callback = false ) {
		}

		public function form( $instance ) {
			?><p><?php
			printf(
				__( 'Please, install and active the %sSiteOrigin Widgets Bundle%s plugin to use these addon settings.' ),
				sprintf(
					'<a href="%s" target="_blank" rel="noopener noreferrer">',
					apply_filters( 'siteorigin_add_installer', true ) ? esc_url( admin_url( 'admin.php?page=siteorigin-installer&highlight=so-widgets-bundle' ) ) : 'https://wordpress.org/plugins/so-widgets-bundle/'
				),
				'</a>'
			);
			?></p><?php
		}

		public function update( $new_instance, $old_instance ) {
			return $old_instance;
		}
	}
}
