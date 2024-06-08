<?php
/*
Plugin Name: SiteOrigin Social Widgets
Description: Expand and personalize your site's social media buttons by adding unique networks and custom icons, enhancing user engagement and connectivity.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/social-widgets/
Tags: Widgets Bundle
Video: 314963167
Requires: so-widgets-bundle/social-media-buttons
*/

class SiteOrigin_Premium_Plugin_Social_Widgets {
	public function __construct() {
		add_filter( 'sow_social_media_buttons_form_options', array( $this, 'form_options' ) );
		add_filter( 'sow_social_media_buttons_networks', array( $this, 'merge_custom_networks' ), 10, 2 );
		add_filter( 'siteorigin_widgets_template_html_sow-social-media-buttons', array( $this, 'premium_template' ), 10, 4 );
		add_filter( 'siteorigin_widgets_less_sow-social-media-buttons', array( $this, 'premium_styles' ), 10, 3 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Add form options for adding custom network to the social media widget.
	 *
	 * @return array
	 */
	public function form_options( $form_options ) {
		siteorigin_widgets_array_insert(
			$form_options,
			'design',
			array(
				'custom_networks' => array(
					'type'        => 'repeater',
					'label'       => 'Custom Networks',
					'description' => __( 'Add your own social networks.', 'siteorigin-premium' ),
					'item_name'   => __( 'New Network', 'siteorigin-premium' ),
					'item_label'  => array(
						'selector'     => "[id*='custom_networks-name']",
						'update_event' => 'change',
						'value_method' => 'val',
					),
					'fields'      => array(
						'name'         => array(
							'type'  => 'text',
							'label' => __( 'Name', 'siteorigin-premium' ),
						),
						'url'          => array(
							'type'  => 'text',
							'label' => __( 'URL', 'siteorigin-premium' ),
						),
						'icon_name'    => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'siteorigin-premium' ),
						),
						'icon_color'   => array(
							'type'    => 'color',
							'default' => '#fff',
							'label'   => __( 'Icon color', 'siteorigin-premium' ),
						),
						'icon_color_hover' => array(
							'type'  => 'color',
							'label' => __( 'Icon hover color', 'siteorigin-premium' ),
							'description' => __( 'This setting will only affect non-image icons.', 'siteorigin-premium' ),
							'state_handler' => array(
								'hover_effects[enabled]' => array( 'show' ),
								'hover_effects[disabled]' => array( 'hide' ),
							),
						),
						'icon_title' => array(
							'type' => 'text',
							'label' => __( 'Icon title', 'siteorigin-premium' ),
						),
						'icon_image'   => array(
							'type'  => 'media',
							'label' => __( 'Icon image', 'siteorigin-premium' ),
						),
						'button_color' => array(
							'type'    => 'color',
							'default' => '#000000',
							'label'   => __( 'Background color', 'siteorigin-premium' ),
						),
						'button_color_hover' => array(
							'type'  => 'color',
							'label' => __( 'Background hover color', 'siteorigin-premium' ),
							'state_handler' => array(
								'hover_effects[enabled]' => array( 'show' ),
								'hover_effects[disabled]' => array( 'hide' ),
							),
						),
						'border_color' => array(
							'type'  => 'color',
							'label' => __( 'Border color', 'siteorigin-premium' ),
							'state_handler' => array(
								'theme[wire]' => array( 'show' ),
								'_else[theme]' => array( 'hide' ),
							),
						),
						'border_hover_color' => array(
							'type'  => 'color',
							'label' => __( 'Border hover color', 'siteorigin-premium' ),
							'state_handler' => array(
								'theme[wire]' => array( 'show' ),
								'_else[theme]' => array( 'hide' ),
							),
						),
					),
				),
			)
		);

		return $form_options;
	}

	/**
	 * Merge custom networks with default networks so they're displayed in the widget.
	 *
	 * @return array
	 */
	public function merge_custom_networks( $networks, $instance ) {
		if ( ! empty( $instance['custom_networks'] ) ) {
			$custom_networks = $instance['custom_networks'];

			foreach ( $custom_networks as $key => $custom ) {
				if ( empty( $custom['icon_name'] ) && empty( $custom['icon_image'] ) ) {
					continue;
				}

				// Is there a network name?
				if ( empty( $custom['name'] ) ) {
					// If an icon image isn't set, use the icon's name.
					if ( empty( $custom['icon_image'] ) ) {
						$custom['name'] = $custom['icon_name'];
					} else {
						// Use the icon image id as the network name.
						$key = $custom['name'] = $custom['icon_image'];
					}
				}

				$name = preg_replace( '/\s/', '_', $custom['name'] );
				$name = sanitize_html_class( $name );
				$custom_networks[ $key ]['name'] = $name;
				$custom_networks[ $key ]['is_custom'] = true;
			}
			$networks = array_merge( $networks, $custom_networks );
		}

		return $networks;
	}

	/**
	 * Replace template or parts of template with premium content.
	 *
	 * @return string
	 */
	public function premium_template( $template_html, $instance, $widget ) {
		if ( empty( $instance['custom_networks'] ) ) {
			return $template_html;
		}

		foreach ( $instance['custom_networks'] as $custom ) {
			if ( empty( $custom['icon_image'] ) ) {
				continue;
			}

			if ( empty( $custom['name'] ) ) {
				$custom['name'] = $custom['icon_image'];
			} else {

			}

			$custom_name = preg_replace( '/\s/', '_', $custom['name'] );
			$custom_name = sanitize_html_class( $custom_name );

			$custom_icon_html = '';

			$attachment = wp_get_attachment_image_src( $custom['icon_image'] );
			if ( ! empty( $attachment ) ) {
				$icon_styles[] = 'background-image: url(' . esc_url( $attachment[0] ) . ')';
				$custom_icon_html .= '<div class="sow-icon-image" style="' . implode( '; ', $icon_styles ) . '"></div>';
			}
			$premium_regex = '/<!--\s*premium-' . $custom_name . '\s*-->[\s\S]*?<!--\s*endpremium\s*-->/';
			$template_html = preg_replace( $premium_regex, $custom_icon_html, $template_html );
		}

		return $template_html;
	}

	/**
	 * Replace LESS or parts of LESS with premium LESS styles.
	 *
	 * @return string
	 */
	public function premium_styles( $less, $instance, $widget ) {
		$less .= '
		a {
			.sow-icon-image {
				width: 1em;
				height: 1em;
				background-size: cover;
				display: inline-block;
			}
		}';

		return $less;
	}
}
