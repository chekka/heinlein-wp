<?php
/*
Plugin Name: SiteOrigin Carousel
Description: Additional settings and styles for the SiteOrigin Carousel widgets.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/carousel
Tags: Widgets Bundle
Video:
Requires: so-widgets-bundle
*/

class SiteOrigin_Premium_Plugin_Carousel {
	public function __construct() {
		add_action( 'init', array( $this, 'add_filters' ) );
		add_action( 'siteorigin_widgets_post_carousel_theme_assets', array( $this, 'register_template_assets' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_filters() {
		if ( class_exists( 'SiteOrigin_Widget_Anything_Carousel_Widget' ) ) {
			add_filter( 'siteorigin_panels_general_style_fields', array( $this, 'style_fields' ), 10, 3 );
			add_filter( 'siteorigin_panels_general_current_styles', array( $this, 'style_migration' ), 10, 4 );

			add_filter( 'siteorigin_widgets_form_options_sow-anything-carousel', array( $this, 'anything_carousel_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_anything_carousel_render_item_content', array( $this, 'anything_carousel_render_item_content' ), 10, 3 );

			add_action( 'siteorigin_premium_version_update', array( $this, 'update_settings_migration' ), 20, 3 );
		}

		if ( class_exists( 'SiteOrigin_Widget_PostCarousel_Widget' ) ) {
			add_action( 'wp_loaded', array( $this, 'post_carousel_register_image_sizes' ) );

			add_filter( 'siteorigin_widgets_form_options_sow-post-carousel', array( $this, 'post_carousel_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_post_carousel_settings_form', array( $this, 'post_carousel_settings_form' ) );
			add_filter( 'siteorigin_widgets_post_carousel_settings_frontend', array( $this, 'post_carousel_settings_frontend' ), 10, 2 );
			add_filter( 'siteorigin_post_carousel_ajax_item_template', array( $this, 'post_carousel_ajax_item_template' ), 10, 2 );
			add_filter( 'siteorigin_widgets_template_variables_sow-post-carousel', array( $this, 'post_carousel_template_vars' ), 10, 2 );
			add_filter( 'siteorigin_widgets_less_file_sow-post-carousel', array( $this, 'post_carousel_add_theme_less' ), 10, 3 );
			add_filter( 'siteorigin_widgets_less_variables_sow-post-carousel', array( $this, 'post_carousel_add_theme_less_vars' ), 10, 2 );
		}
	}

	public function update_settings_migration( $new_version, $old_version ) {
		$addons = SiteOrigin_Premium::single()->get_active_addons();

		// If upgrading from version <= 1.32.1, activate the Anchor ID addon.
		if ( version_compare( $old_version, '1.32.1', '<=' ) && ! empty( $addons['plugin/anchor-id'] ) ) {
			SiteOrigin_Premium::single()->set_addon_active( 'plugin/anchor-id', true );
		}
	}

	public function style_fields( $fields, $post_id, $args ) {
		// To prevent display issues, we need to remove Row Layout options.
		if (
			! empty( $args ) &&
			$args['builderType'] === 'anything_carousel_panel_builder' &&
			isset( $fields['row_stretch'] )
		) {
			unset( $fields['row_stretch'] );
		}
		return $fields;
	}

	public function style_migration( $styles, $post_id, $instance, $args ) {
		// Reset Row Layout.
		if (
			! empty( $args ) &&
			$args['builderType'] === 'anything_carousel_panel_builder' &&
			! empty( $styles['row_stretch'] )
		) {
			$styles['row_stretch'] = '';
		}

		return $styles;
	}

	public function anything_carousel_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		// Builder field.
		$items_fields = $form_options['items']['fields'];

		if ( array_key_exists( 'content_text', $items_fields ) ) {
			$position = 'content_text';
			$items_fields['content_text']['state_handler'] = array(
				'content_type_{$repeater}[text]' => array( 'show' ),
				'_else[content_type_{$repeater}]' => array( 'hide' ),
			);
		} else {
			$position = count( $items_fields );
		}

		// The Builder field currently only works in some contexts so we only output it in those contexts for now.
		if ( is_admin() ||
			( defined( 'REST_REQUEST' ) && function_exists( 'register_block_type' ) ) ||
			! empty( $GLOBALS['SITEORIGIN_WIDGET_BLOCK_RENDER'] )
		) {
			$add_fields = array(
				'content_type' => array(
					'type' => 'radio',
					'label' => __( 'Content type', 'siteorigin-premium' ),
					'options' => array(
						'text' => __( 'Text', 'siteorigin-premium' ),
						'layout' => __( 'Layout builder', 'siteorigin-premium' ),
					),
					'default'=> 'text',
					'state_emitter' => array(
						'callback' => 'select',
						'args' => array( 'content_type_{$repeater}' ),
					),
				),
				'content_layout' => array(
					'type' => 'builder',
					'label' => __( 'Content', 'siteorigin-premium' ),
					'builder_type' => 'anything_carousel_panel_builder',
					'state_handler' => array(
						'content_type_{$repeater}[layout]' => array( 'show' ),
						'_else[content_type_{$repeater}]' => array( 'hide' ),
					),
				),
			);

			siteorigin_widgets_array_insert( $items_fields, $position, $add_fields );
			$form_options['items']['fields'] = $items_fields;
		}

		return $form_options;
	}

	public function anything_carousel_render_item_content( $content, $item, $instance ) {
		if ( ! empty( $item['content_type'] ) && $item['content_type'] === 'layout' ) {
			if ( function_exists( 'siteorigin_panels_render' ) ) {
				$content_builder_id = substr( md5( json_encode( $item['content_layout'] ) ), 0, 8 );

				// Remove non-standard row layouts as they're not compatible.
				if ( ! empty( $item['content_layout']['grids'] ) ) {
					foreach( $item['content_layout']['grids'] as $grid_id => $grid ) {
						if ( empty( $grid['style' ] ) ) {
							continue;
						}

						if ( isset( $grid['style']['row_stretch'] ) ) {
							unset( $item['content_layout']['grids'][ $grid_id ]['style']['row_stretch'] );
						}
					}
				}

				$content = siteorigin_panels_render( 'w' . $content_builder_id, true, $item['content_layout'] );
			} else {
				$content = __( 'This field requires Page Builder.', 'siteorigin-premium' );
			}
		}

		return $content;
	}

	public function post_carousel_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		// Add themes preset field.
		$themes = json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'post-carousel/themes.json' ), true );

		// Inject base slides_to_scroll value.
		$themes['base']['values']['responsive']['desktop']['slides_to_scroll'] = $form_options['responsive']['fields']['desktop']['fields']['slides_to_scroll']['placeholder'];

		siteorigin_widgets_array_insert( $form_options['design']['fields'], 'thumbnail', array(
			'theme' => array(
				'type' => 'presets',
				'label' => __( 'Theme', 'siteorigin-premium' ),
				'default_preset' => 'base',
				'description' => __( 'Please, allow a couple of seconds for the theme settings to be applied.', 'siteorigin-premium' ),
				'options' => $themes,
				'state_emitter' => array(
					'callback' => 'select',
					'args' => array( 'selected_theme' ),
				),
			),
		) );

		// Add additional settings to Design > Post Thumbnail section.
		$form_options['design']['fields']['thumbnail']['fields']['thumbnail_overlay_color'] = array(
			'type' => 'color',
			'label' => __( 'Thumbnail overlay color', 'siteorigin-premium' ),
			'default' => '#000',
		);
		$form_options['design']['fields']['thumbnail']['fields']['border_radius'] = array(
			'type' => 'slider',
			'label' => __( 'Border radius', 'siteorigin-premium' ),
			'default' => '6',
			'min' => 0,
			'max' => 100,
		);

		// Hide the Responsive Slides to Show setting for non-overlay themes.
		$slides_to_show_state_handler = array(
			'selected_theme[overlay]' => array( 'show' ),
				'_else[selected_theme]' => array( 'hide' ),
		);
		$form_options['responsive']['fields']['desktop']['fields']['slides_to_show']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['desktop']['fields']['navigation_dots']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['tablet']['fields']['landscape']['fields']['slides_to_show']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['tablet']['fields']['landscape']['fields']['navigation_dots']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['tablet']['fields']['portrait']['fields']['slides_to_show']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['tablet']['fields']['portrait']['fields']['navigation_dots']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['mobile']['fields']['slides_to_show']['state_handler'] = $slides_to_show_state_handler;
		$form_options['responsive']['fields']['mobile']['fields']['navigation_dots']['state_handler'] = $slides_to_show_state_handler;

		// Add Design > Category section.
		siteorigin_widgets_array_insert(
			$form_options['design']['fields'],
			'navigation',
			array(
				'category' => array(
					'type' => 'section',
					'label' => __( 'Category', 'siteorigin-premium' ),
					'hide' => true,
					'fields' => array(
						'color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'siteorigin-premium' ),
						),
						'size' => array(
							'type' => 'measurement',
							'label' => __( 'Font size', 'siteorigin-premium' ),
							'default' => '13px',
						),
						'background_color' => array(
							'type' => 'color',
							'label' => __( 'Background color', 'siteorigin-premium' ),
							'default' => '#929292',
						),
					),
				),
			)
		);

		// Additonal Design > Navigation section.
		siteorigin_widgets_array_insert(
			$form_options['design']['fields']['navigation']['fields'],
			'navigation_background',
			array(
				'dots_color' => array(
					'type' => 'color',
					'label' => __( 'Dots color', 'siteorigin-premium' ),
					'default' => '#bebebe',
				),
				'dots_color_hover' => array(
					'type' => 'color',
					'label' => __( 'Dots selected and hover color', 'siteorigin-premium' ),
					'default' => '#f14e4e',
				),
				'nav_top_margin' => array(
					'type' => 'measurement',
					'label' => __( 'Navigation top margin', 'siteorigin-premium' ),
					'description' => __( 'The space between the posts and navigation elements.', 'siteorigin-premium' ),
					'default' => '38px',
				),
			)
		);

		return $widget->dynamic_preset_state_handler(
			'selected_theme',
			$themes,
			$form_options,
			true
		);
	}

	private static function is_non_default_theme( $instance ) {
		return ! empty( $instance ) &&
			isset( $instance['design'] ) &&
			isset( $instance['design']['theme'] ) &&
			$instance['design']['theme'] != 'base';
	}

	/**
	 * This change allows for the Slides to Show Responsive settings to show when the Overlay theme is active.
	 */
	public function post_carousel_settings_form( $settings ) {
		unset( $settings['slides_to_show'] );
		unset( $settings['navigation_dots_label'] );

		return $settings;
	}

	public function post_carousel_settings_frontend( $settings, $instance ) {
		if (
			! self::is_non_default_theme( $instance ) ||
			! file_exists( plugin_dir_path( __FILE__ ) . 'post-carousel/templates/' . $instance['design']['theme'] . '.php' )
		) {
			unset( $settings['dots'] );
		} else {
			$settings['theme'] = $instance['design']['theme'];
			$settings['appendDots'] = 'true';
		}

		return $settings;
	}

	public function register_template_assets() {
		wp_register_style(
			'sow-post-carousel-overlay',
			plugin_dir_url( __FILE__ ) . 'post-carousel/css/overlay.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	public function post_carousel_register_image_sizes() {
		add_image_size( 'sow-post-carousel-overlay-theme', 360, 476, true );
	}

	public function post_carousel_ajax_item_template( $template, $instance ) {
		if (
			self::is_non_default_theme( $instance ) &&
			file_exists( plugin_dir_path( __FILE__ ) . 'post-carousel/templates/' . $instance['design']['theme'] . '.php' )
		) {
			$template = plugin_dir_path( __FILE__ ) . 'post-carousel/templates/' . $instance['design']['theme'] . '.php';
		}

		return $template;
	}

	public function post_carousel_template_vars( $template_vars, $instance ) {
		if (
			self::is_non_default_theme( $instance ) &&
			file_exists( plugin_dir_path( __FILE__ ) . 'post-carousel/templates/' . $instance['design']['theme'] . '.php' )
		) {
			if ( wp_style_is( 'sow-post-carousel-' . $instance['design']['theme'], 'registered' ) ) {
				wp_enqueue_style( 'sow-post-carousel-' . $instance['design']['theme'] );
			}
			$template_vars['settings']['item_template'] = plugin_dir_path( __FILE__ ) . 'post-carousel/templates/' . $instance['design']['theme'] . '.php';
			$template_vars['settings']['navigation'] = 'container';

			$size = siteorigin_widgets_get_image_size( $instance['image_size'] );

			if ( ! ( empty( $size['width'] ) || empty( $size['height'] ) ) ) {
				$template_vars['settings']['height'] = $size['height'] . 'px';
			}

			unset( $template_vars['settings']['attributes']['variable_width'] );
		}

		return $template_vars;
	}

	public function post_carousel_add_theme_less( $less_file, $instance, $widget ) {
		if ( self::is_non_default_theme( $instance ) ) {
			$less_file = plugin_dir_path( __FILE__ ) . 'post-carousel/less/' . $instance['design']['theme'] . '.less';
		}

		return $less_file;
	}

	private static function less_var( $settings, $var, $default = null ) {
		return ! empty( $settings[ $var ] ) ? $settings[ $var ] : $default;
	}

	public function post_carousel_add_theme_less_vars( $less_vars, $instance ) {
		if ( self::is_non_default_theme( $instance ) ) {
			// Post Thumbnail.
			$less_vars['thumbnail_overlay_color'] = $this::less_var(
				$instance['design']['thumbnail'],
				'thumbnail_overlay_color'
			);

			$less_vars['thumbnail_border_radius'] = $this::less_var(
				$instance['design']['thumbnail'],
				'border_radius'
			) . 'px';

			// Post Title.
			if (
				! empty( $instance['design']['item_title'] ) &&
				! empty( $instance['design']['item_title']['font'] )
			) {
				$item_title_font = siteorigin_widget_get_font( $instance['design']['item_title']['font'] );
				$less_vars['title_font'] = $item_title_font['family'];

				if ( ! empty( $item_title_font['weight'] ) ) {
					$less_vars['title_font_style'] = $item_title_font['style'];
					$less_vars['title_font_weight'] = $item_title_font['weight_raw'];
				}
			}

			// Category.
			$less_vars['category_color'] = $this::less_var(
				$instance['design']['category'],
				'color',
				'#F14E4E'
			);
			$less_vars['category_size'] = $this::less_var(
				$instance['design']['category'],
				'size',
				'12px'
			);
			$less_vars['category_background'] = $this::less_var(
				$instance['design']['category'],
				'background_color',
				'#fff'
			);

			// Navigation.
			$less_vars['navigation_arrow_margin'] = $this::less_var(
				$instance['design']['navigation'],
				'arrow_margin'
			);

			$less_vars['navigation_dots_color'] = $this::less_var(
				$instance['design']['navigation'],
				'dots_color',
				'#bebebe'
			);

			$less_vars['navigation_dots_color_hover'] = $this::less_var(
				$instance['design']['navigation'],
				'dots_color_hover',
				'#f14e4e'
			);

			$less_vars['navigation_dots_top_margin'] = $this::less_var(
				$instance['design']['navigation'],
				'nav_top_margin',
				'40px'
			);

			$less_vars['navigation_arrows'] = isset( $instance['carousel_settings']['arrows'] ) ? $instance['carousel_settings']['arrows'] : 1;
		}

		return $less_vars;
	}
}
