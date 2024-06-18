<?php

/**
 * This metabox is the box that appears when users create new custom layouts..
 */
class SiteOrigin_Premium_Metabox extends SiteOrigin_Widget {
	public function __construct() {
		parent::__construct(
			'siteorigin-premium',
			__( 'SiteOrigin Premium', 'siteorigin-premium' ),
			array(
				'has_preview' => false,
			),
			array(),
			false,
			plugin_dir_path( __FILE__ )
		);
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ), 10, 3 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_metabox( $post_type ) {
		$excluded_types = apply_filters( 'siteorigin_premium_metabox_excluded_post_types', array(
			'so_mirror_widget',
			'so_custom_post_type',
			'acf-field-group',
			'acf-post-type',
			'acf-taxonomy',
			'acf-field-group',
		) );

		if ( ! in_array( $post_type, $excluded_types ) ) {
			add_meta_box(
				'siteorigin_premium_metabox',
				__( 'SiteOrigin Premium', 'siteorigin-premium' ),
				array( $this, 'render_metabox' ),
				$post_type,
				'advanced',
				'default',
				array(
					'__block_editor_compatible_meta_box' => true,
				)
			);
		}
	}

	public function get_widget_form() {
		$form_options = apply_filters( 'siteorigin_premium_metabox_form_options', array(
			'general' => array(
				'type' => 'section',
				'label' => __( 'General', 'siteorigin-premium' ),
				'tab' => true,
				'hide' => true,
				'fields' => array(),
			),
		) );

		// If there aren't any general fields, remove the tab.
		if ( empty( $form_options['general']['fields'] ) ) {
			unset( $form_options['general'] );
		}

		if ( class_exists( 'SiteOrigin_Widget_Field_Tabs' ) ) {
			// If WB is new enough to support the Tabs field, add it.
			$tabs = array();

			foreach ( $form_options as $id => $field ) {
				if ( isset( $field['tab'] ) ) {
					$tabs[ $id ] = $field['label'];
				}
			}

			if ( ! empty( $tabs ) ) {
				$form_options = array(
					'tabs' => array(
						'type' => 'tabs',
						'tabs' => $tabs,
					),
				) + $form_options;
			}
		}

		return $form_options;
	}

	public function render_metabox( $post ) {
		$meta = get_post_meta( $post->ID, 'siteorigin_premium_meta', true );

		$this->form(
			apply_filters(
				'siteorigin_premium_metabox_meta',
				! empty( $meta ) ? $meta : array(),
				$post
			)
		);
		wp_nonce_field( 'siteorigin_premium_metabox_save', '_siteorigin_premium_metabox_save_nonce' );
	}

	/**
	 * Accounts empty values being present in a form options array.
	 *
	 * @param array $form_options The form options array.
	 * @param array $values The values array.
	 * @return array The modified instance array.
	 */
	private function account_for_empty( $form_options, $values ) {
		$instance = array();

		foreach ( $form_options as $id => $field ) {
			if ( is_array( $field ) ) {
				$instance[ $id ] = $this->account_for_empty(
					$field,
					isset( $values[ $id ] ) ? $values[ $id ] : array()
				);
			} else {
				if ( isset( $values[ $id ] ) ) {
					$instance[ $id ] = $values[ $id ];
				} else {
					$instance[ $id ] = false;
				}
			}
		}

		return $instance;
	}

	public function metabox_save( $post_id ) {
		if (
			empty( $_POST['_siteorigin_premium_metabox_save_nonce'] ) ||
			! wp_verify_nonce( $_POST['_siteorigin_premium_metabox_save_nonce'], 'siteorigin_premium_metabox_save' ) ||
			! current_user_can( 'edit_post', $post_id )
		) {
			return;
		}

		if (
			! empty( $_POST['widget-siteorigin-premium'] ) &&
			! empty( $_POST['widget-siteorigin-premium'][1] )
		) {
			$values = $_POST['widget-siteorigin-premium'][1];

			$form_options = $this->get_widget_form();
			unset( $form_options['tabs'] );

			// Load defaults, and account for empty values.
			$instance = $this->add_defaults( $form_options, $values );
			$instance = $this->account_for_empty( $instance, $values );

			$meta = get_post_meta( $post_id, 'siteorigin_premium_meta', true );
			update_post_meta(
				$post_id,
				'siteorigin_premium_meta',
				$this->update(
					$instance,
					! empty( $meta ) ? $meta : false,
					'metabox'
				)
			);
		}

		do_action( 'siteorigin_premium_metabox_save', $post_id );
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_script(
			'siteorigin-premium-metabox',
			plugin_dir_url( __FILE__ ) . 'js/metabox' . SOW_BUNDLE_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SOW_BUNDLE_VERSION
		);
		wp_enqueue_style(
			'siteorigin-premium-metabox',
			plugin_dir_url( __FILE__ ) . 'css/metabox.css',
			SOW_BUNDLE_VERSION
		);
	}
}
