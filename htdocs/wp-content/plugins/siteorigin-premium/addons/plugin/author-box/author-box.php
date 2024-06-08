<?php
/*
Plugin Name: SiteOrigin Author Box
Description: Automatically append author boxes to posts, featuring social links, recent articles, and bios to create engaging author presentations across multiple post types.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/author-box/
Tags: Widgets Bundle
Requires: so-widgets-bundle/author-box
*/

class SiteOrigin_Premium_Plugin_Author_Box {
	private $hooks = array();
	private $widget_networks;
	private $isGlobal = false;

	public function __construct() {
		add_action( 'init', array( $this, 'add_filters' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_filters() {
		if ( class_exists( 'SiteOrigin_Widget_Author_Box_Widget' ) ) {
			add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ) );

			add_filter( 'siteorigin_widgets_form_options_sow-author-box', array( $this, 'add_form_options' ), 10, 2 );

			add_filter( 'the_content', array( $this, 'add_author_box' ), 11 );

			add_action( 'siteorigin_widgets_author_box_before', array( $this, 'setup_widget_hooks' ) );
			add_action( 'siteorigin_widgets_author_box_after', array( $this, 'remove_widget_hooks' ), 11 );

			add_filter( 'siteorigin_widgets_block_exclude_widget', array( $this, 'exclude_from_widgets_block_cache' ), 10, 2 );

			add_action( 'show_user_profile', array( $this, 'add_social_media_to_profile' ) );
			add_action( 'edit_user_profile', array( $this, 'add_social_media_to_profile' ) );
			add_action( 'personal_options_update', array( $this, 'add_social_media_to_profile_save' ) );
			add_action( 'edit_user_profile_update', array( $this, 'add_social_media_to_profile_save' ) );
		}
	}

	public function exclude_from_widgets_block_cache( $exclude, $widget_class ) {
		if ( $widget_class == 'SiteOrigin_Widget_Recent_Posts_Widget' ) {
			$exclude = true;
		}

		return $exclude;
	}

	public function get_settings_form() {
		if ( class_exists( 'SiteOrigin_Widget_Author_Box_Widget' ) ) {
			$settings = array(
				'position' => array(
					'type' => 'radio',
					'label' => __( 'Position', 'siteorigin-premium' ),
					'default' => 'after',
					'options' => array(
						'before' => __( 'Before Content', 'siteorigin-premium' ),
						'after' => __( 'After Content', 'siteorigin-premium' ),
					),
				),
				'types' => array(
					'type' => 'checkboxes',
					'label' => __( 'Enabled Post Types', 'siteorigin-premium' ),
					'options' => SiteOrigin_Premium_Utility::single()->get_post_types(),
				),
				'widget' => array(
					'type' => 'widget',
					'label' => __( 'Author Box Settings', 'siteorigin-premium' ),
					'class' => 'SiteOrigin_Widget_Author_Box_Widget',
				),
			);
		} elseif ( defined( 'SOW_BUNDLE_VERSION' ) && version_compare( SOW_BUNDLE_VERSION, '1.46.4', '<' ) ) {
			$settings = array(
				'html' => array(
					'type' => 'html',
					'markup' => sprintf( __( 'This addon requires SiteOrigin Widgets Bundle X or higher. You have version %s installed. Please update the SiteOrigin Widgets Bundle.', 'siteorigin-premium' ), SOW_BUNDLE_VERSION ),
				),
			);
		} else {
			$settings = array(
				'html' => array(
					'type' => 'html',
					'markup' => __( 'This addon requires the Author Box Widget to be enabled at Plugins > SiteOrigin Widgets.', 'siteorigin-premium' ),
				),
			);
		}

		return new SiteOrigin_Premium_Form(
			'so-addon-author-box-settings',
			$settings
		);
	}

	public function metabox_options( $form_options ) {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/author-box' );

		if ( empty( $settings ) ) {
			return $form_options;
		}

		$types = is_array( $settings['types'] ) ? $settings['types'] : array();

		$type_status = in_array( get_post_type(), $types ) ? 'on' : 'off';

		$form_options['general']['fields']["author_box_$type_status"] = array(
			'type' => 'checkbox',
			'label' => __( 'Display Author Box', 'siteorigin-premium' ),
			'default' => $type_status === 'on' ? true : false,
		);

		return $form_options;
	}

	public function add_form_options( $form_options, $widget ) {
		if ( class_exists( 'SiteOrigin_Widget_Recent_Posts_Widget' ) ) {
			$form_options['settings']['fields']['recent_posts'] = array(
				'type' => 'checkbox',
				'label' => __( 'Show Recent Posts By Author', 'siteorigin-premium' ),
				'state_emitter' => array(
					'callback' => 'conditional',
					'args' => array(
						'recent_posts[show]: val',
						'recent_posts[hide]: ! val',
					),
				),
			);
		}

		if ( class_exists( 'SiteOrigin_Widget_SocialMediaButtons_Widget' ) ) {
			$form_options['settings']['fields']['social_media_buttons'] = array(
				'type' => 'checkbox',
				'label' => __( 'Add Social Media Buttons', 'siteorigin-premium' ),
				'state_emitter' => array(
					'callback' => 'conditional',
					'args' => array(
						'social_media_buttons[show]: val',
						'social_media_buttons[hide]: ! val',
					),
				),
			);
		}

		if ( class_exists( 'SiteOrigin_Widget_Recent_Posts_Widget' ) ) {
			$form_options['settings']['fields']['recent_posts_widget'] = array(
				'type' => 'widget',
				'label' => __( 'Recent Posts' , 'siteorigin-premium' ),
				'hide' => true,
				'class' => 'SiteOrigin_Widget_Recent_Posts_Widget',
				'state_handler' => array(
					'recent_posts[show]' => array( 'show' ),
					'recent_posts[hide]' => array( 'hide' ),
				),
			);
		}

		if ( class_exists( 'SiteOrigin_Widget_SocialMediaButtons_Widget' ) ) {
			$form_options['settings']['fields']['social_media_buttons_widget'] = array(
				'type' => 'widget',
				'label' => __( 'Social Media Buttons' , 'siteorigin-premium' ),
				'hide' => true,
				'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
				'form_filter' => array( $this, 'filter_social_media_buttons_widget_form' ),
				'state_handler' => array(
					'social_media_buttons[show]' => array( 'show' ),
					'social_media_buttons[hide]' => array( 'hide' ),
				),
			);
		}

		return $form_options;
	}

	public function filter_social_media_buttons_widget_form( $form_fields ) {
		unset( $form_fields['title'] );

		$form_fields['design']['fields']['theme']['default'] = 'wire';
		$form_fields['design']['fields']['padding']['default'] = 'low';
		$form_fields['design']['fields']['align']['default'] = 'center';

		$positions = json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'data/positions.json' ), true );

		// Is this the global settings form?
		if ( ! empty( $_GET['action'] ) && $_GET['action'] == 'so_premium_addon_settings_form' ) {
			$form_fields['networks']['fields']['url']['description'] = __( 'The URL is used as a prefix for the authors username.', 'siteorigin-premium' );

			// Form structure is different for the global settings form so
			// we need to modify the positions array to reflect that.
			foreach( $positions as $key => $position ) {
				$positions[ $key ]['values']['widget'] = array(
					'settings' => $position['values']['settings'],
				);

				unset( $positions[ $key ]['values']['settings'] );
			}
		}

		siteorigin_widgets_array_insert(
			$form_fields,
			'networks',
			array(
				'position' => array(
					'type' => 'presets',
					'label' => __( 'Position', 'so-widgets-bundle' ),
					'default_preset' => 'avatar_below',
					'options' => $positions,
				),

				'margin_around' => array(
					'type' => 'multi-measurement',
					'label' => __( 'Margin', 'siteorigin-premium' ),
					'default' => '10px 0px 0px 0px',
					'measurements' => array(
						'top' => __( 'Top', 'so-widgets-bundle' ),
						'right' => __( 'Right', 'so-widgets-bundle' ),
						'bottom' => __( 'Bottom', 'so-widgets-bundle' ),
						'left' => __( 'Left', 'so-widgets-bundle' ),
					),
				),
			)
		);

		if ( class_exists( 'SiteOrigin_Widget_Recent_Posts_Widget' ) ) {
			$form_fields['networks']['position']['options']['recent_posts'] = __( 'After Recent posts', 'siteorigin-premium' );
		}

		// Change mobile align default to center to match the default alignment for the author box.
		$form_fields['design']['fields']['mobile_align']['default'] = 'center';

		return $form_fields;
	}

	private function social_media_profile_data() {
		$widget_class = 'SiteOrigin_Widget_SocialMediaButtons_Widget';
		if ( ! class_exists( $widget_class ) ) {
			return;
		}

		$addon_settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/author-box' );

		// Ensure everything is enabled.
		if (
			empty( $addon_settings['widget'] ) ||
			empty( $addon_settings['widget']['settings'] )
		) {
			return;
		}

		$settings = $addon_settings['widget']['settings'];
		if (
			empty( $settings['social_media_buttons'] ) ||
			empty( $settings['social_media_buttons_widget'] ) ||
			empty( $settings['social_media_buttons_widget']['networks'] )
		) {
			return;
		}

		// Okay! Everything is enabled. Let's return the networks.
		$networks = array();
		$this->get_networks_from_array(
			$settings['social_media_buttons_widget']['networks'],
			$networks
		);

		$this->get_networks_from_array(
			$settings['social_media_buttons_widget']['custom_networks'],
			$networks
		);

		return $networks;
	}

	private function get_networks_from_array( $array = array(), & $networks = array() ) {
		foreach ( $array as $network ) {
			// We use the name as an id so it must be set.
			if ( empty( $network['name'] ) ) {
				continue;
			}

			$networks[ $network['name'] ] = ! empty( $network['url'] ) ? $network['url'] : '';
		}
		return $networks;
	}

	public function add_social_media_to_profile( $user ) {
		$networks = $this->social_media_profile_data();
		if ( empty( $networks ) ) {
			return;
		}

		// Get the user networks.
		$user_meta = get_user_meta( $user->ID, 'siteorigin_premium_social_media_urls', true ) ?: array();

		wp_nonce_field( 'author_box_social_media_update', '_author_box_social_media' );


		// Load the Social Media Buttons widget networks.
		if ( empty( $this->widget_networks ) ) {
			$this->widget_networks = include plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . '/widgets/social-media-buttons/data/networks.php';
		}

		include SiteOrigin_Premium::dir_path( __FILE__ ) . 'tpl/social-media-form.php';
	}

	public function add_social_media_to_profile_save( $user_id ) {
		if ( empty( $_POST['so_premium_author_bio_social_media'] ) ) {
			return;
		}

		// Security checks.
		if (
			! current_user_can( 'edit_user', $user_id ) ||
			! isset( $_POST['_author_box_social_media'] ) ||
			! wp_verify_nonce(
				$_POST['_author_box_social_media'],
				'author_box_social_media_update'
			)
		) {
			wp_die( __( 'Security check failed', 'siteorigin-premium' ) );
		}

		$networks = $this->social_media_profile_data();
		if ( empty( $networks ) ) {
			return;
		}

		$saved_networks = array();
		$user_networks = $_POST['so_premium_author_bio_social_media'];
		foreach ( $user_networks as $user_network_name => $user_network_user ) {
			// Ensure the network is valid.
			if ( ! isset( $networks[ $user_network_name ] ) ) {
				continue;
			}

			$saved_networks[ $user_network_name ] = sanitize_text_field( $user_network_user );
		}

		// Delete the meta if there are no set networks.
		if ( empty( $saved_networks ) ) {
			delete_user_meta( $user_id, 'siteorigin_premium_social_media_urls' );
			return;
		}

		update_user_meta( $user_id, 'siteorigin_premium_social_media_urls', $saved_networks );
	}

	public function setup_widget_hooks( $instance ) {
		$settings = $instance['settings'];
		if (
			! empty( $settings['recent_posts'] ) &&
			! empty( $settings['recent_posts_widget'] )
		) {
			add_action( 'siteorigin_widgets_author_box_description_below', array( $this, 'add_recent_posts_widget' ) );
			$this->hooks['siteorigin_widgets_author_box_description_below'] = 'add_recent_posts';
		}

		if (
			empty( $settings['social_media_buttons'] ) ||
			empty( $settings['social_media_buttons_widget'] )
		) {
			return;
		}

		switch ( $settings['social_media_buttons_widget']['position'] ) {
			case 'avatar_above':
				$hook = 'siteorigin_widgets_author_box_avatar_above';
				break;

			case 'avatar_below':
				$hook = 'siteorigin_widgets_author_box_avatar_below';
				break;
			case 'author_above':
				$hook = 'siteorigin_widgets_author_box_description_above';
				break;
			case 'title_inline':
				$hook = 'siteorigin_widgets_author_box_description_inline';
				break;

			case 'bio_before':
				$hook = 'siteorigin_widgets_author_box_description_bio_before';
				break;
			case 'bio_after':
				$hook = 'siteorigin_widgets_author_box_description_bio_after';
				break;

			case 'recent_posts':
				$hook = 'siteorigin_widgets_author_box_description_below';
				break;

			default:
				$hook = 'siteorigin_widgets_author_box_avatar_above';
				break;
		}
		add_action( $hook, array( $this, 'add_social_media_buttons_widget' ) );
		$this->hooks[ $hook ] = 'add_social_media_buttons_widget';
	}

	public function remove_widget_hooks( $instance ) {
		foreach ( $this->hooks as $hook => $method ) {
			remove_action( $hook, array( $this, $method ) );
		}

		$this->hooks = array();
	}

	public function add_recent_posts_widget( $instance ) {
		$widget_class = 'SiteOrigin_Widget_Recent_Posts_Widget';

		if ( ! class_exists( $widget_class ) ) {
			return;
		}
		$widget = 'recent-posts';
		$widget_settings = $instance['settings']['recent_posts_widget'];
		include SiteOrigin_Premium::dir_path( __FILE__ ) . 'tpl/widget.php';
	}

	public function add_social_media_buttons_widget( $instance ) {
		$widget_class = 'SiteOrigin_Widget_SocialMediaButtons_Widget';

		if ( ! class_exists( $widget_class ) ) {
			return;
		}

		if ( empty( $instance['settings']['social_media_buttons_widget'] ) ) {
			return;
		}

		$container_css = '';
		$widget_settings = $instance['settings']['social_media_buttons_widget'];
		if ( $widget_settings['position'] == 'title_inline' ) {
			$container_css = 'display: inline-block;';
		}

		if ( ! empty( $widget_settings['margin_around'] ) ) {
			$container_css .= 'margin: ' . esc_attr( $widget_settings['margin_around'] ) . ';';
		}

		// Get the user id of the author of the post.
		$user_id = get_the_author_meta( 'ID' );
		if ( empty( $user_id ) ) {
			return;
		}

		$user_meta = get_user_meta( $user_id, 'siteorigin_premium_social_media_urls', true ) ?: array();
		// If the user hasn't set up their socials, and this widget is being output via the global settings, stop output.
		if ( $this->isGlobal && empty( $user_meta ) ) {
			return;
		}

		if ( $this->isGlobal ) {
			$this->add_social_media_buttons_widget_network_setup(
				'networks',
				$widget_settings,
				$user_meta
			);

			$this->add_social_media_buttons_widget_network_setup(
				'custom_networks',
				$widget_settings,
				$user_meta
			);
		}

		$widget = 'social-media-buttons';
		include SiteOrigin_Premium::dir_path( __FILE__ ) . 'tpl/widget.php';
	}

	private function add_social_media_buttons_widget_network_setup( $field, & $widget_settings, $user_meta = array() ) {

		foreach ( $widget_settings[ $field ] as $key => $network ) {
			if (
				// Does the network have a name?
				empty( $network['name'] ) ||
				(
					$this->isGlobal &&
					// Has the user set up this network?
					empty( $user_meta[ $network['name'] ] )
				)
			) {
				unset( $widget_settings[ $field ][ $key ] );
				continue;
			}

			$url = '';
			if ( ! empty( $network['url'] ) ) {
				$url .= $network['url'];
				// Ensure the URL has a trailing slash.
				if ( substr( $url, -1 ) !== '/' ) {
					$url .= '/';
				}
			}
			$url .= $user_meta[ $network['name'] ];

			$widget_settings[ $field ][ $key ]['url'] = esc_url( $url );
		}
	}

	public function add_author_box( $content ) {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/author-box' );

		if ( ! is_singular() ) {
			return $content;
		}

		if ( ! SiteOrigin_Premium_Utility::single()->is_addon_enabled_for_post( $settings, 'author_box' ) ) {
			return $content;
		}

		global $wp_widget_factory;
		$the_widget = $wp_widget_factory->widgets['SiteOrigin_Widget_Author_Box_Widget'];

		$this->isGlobal = true;
		ob_start();
		$the_widget->widget( array(), $settings['widget'] );
		$box = ob_get_clean();
		if ( $settings['position'] == 'before' ) {
			return $box . $content;
		} else {
			return $content . $box;
		}
	}
}
