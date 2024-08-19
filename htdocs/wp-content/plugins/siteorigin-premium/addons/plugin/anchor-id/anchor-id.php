<?php
/*
Plugin Name: SiteOrigin Anchor ID
Description: Link directly to specific slides, tabs, accordion panels, and carousel items using easy-to-set anchor IDs, maintaining user focus and engagement.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/anchor-id/
Tags: Widgets Bundle
Requires: so-widgets-bundle/layout-slider, so-widgets-bundle/hero, so-widgets-bundle/slider
*/

class SiteOrigin_Premium_Plugin_Anchor_Id {
	private $offset_added;

	public function __construct() {
		add_action( 'init', array( $this, 'add_filters' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_filters() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_js' ) );

		if ( class_exists( 'SiteOrigin_Widget_LayoutSlider_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-layout-slider', array( $this, 'add_slider_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_enqueue_frontend_scripts_sow-layout-slider', array( $this, 'slider_enqueue_js' ), 10, 1 );
		}

		if ( class_exists( 'SiteOrigin_Widget_Hero_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-hero', array( $this, 'add_slider_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_enqueue_frontend_scripts_sow-hero', array( $this, 'slider_enqueue_js' ), 10, 1 );
		}

		if ( class_exists( 'SiteOrigin_Widget_Slider_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-slider', array( $this, 'add_slider_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_enqueue_frontend_scripts_sow-slider', array( $this, 'slider_enqueue_js' ), 10, 1 );
		}

		if ( class_exists( 'SiteOrigin_Widget_Accordion_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-accordion', array( $this, 'add_accordion_tabs_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_wrapper_data_sow-accordion', array( $this, 'add_accordion_tabs_front_end_params' ), 10, 3 );
			add_action( 'siteorigin_widgets_enqueue_frontend_scripts_sow-accordion', array( $this, 'accordion_enqueue_js' ), 9 );
		}

		if ( class_exists( 'SiteOrigin_Widget_Tabs_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-tabs', array( $this, 'add_accordion_tabs_form_options' ), 10, 2 );
			add_filter( 'siteorigin_widgets_wrapper_data_sow-tabs', array( $this, 'add_accordion_tabs_front_end_params' ), 10, 3 );
			add_action( 'siteorigin_widgets_enqueue_frontend_scripts_sow-tabs', array( $this, 'tabs_enqueue_js' ), 9 );
		}

		if ( class_exists( 'SiteOrigin_Widget_Anything_Carousel_Widget' ) ) {
			add_filter( 'siteorigin_widgets_form_options_sow-anything-carousel', array( $this, 'add_anything_carousel_form_options' ), 10, 2 );

			add_filter( 'siteorigin_widgets_template_variables_sow-anything-carousel', array( $this, 'anything_carousel_add_anchor' ), 10, 2 );
			add_filter( 'siteorigin_widgets_frontend_scripts_sow-anything-carousel', array( $this, 'anything_carousel_enqueue_js' ), 10, 1 );
		}
	}

	public function register_js() {
		wp_register_script(
			'so-premium-anchor-id',
			plugin_dir_url( __FILE__ ) . 'js/anchor-id' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_localize_script(
			'so-premium-anchor-id',
			'soPremiumAnchorId',
			array(
				'scrollto_offset' => (int) apply_filters( 'siteorigin_premium_anchor_id_scrollto_offset', 90 ),
			)
		);
	}

	public function add_slider_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$form_options['controls']['fields']['use_anchor_tags'] = array(
			'type' => 'checkbox',
			'label' => __( 'Add slide anchor ID in the URL', 'siteorigin-premium' ),
			'state_emitter' => array(
				'callback' => 'conditional',
				'args' => array(
					'anchor_tags[show]: val',
					'anchor_tags[hide]: ! val',
				),
			),
		);

		$form_options['controls']['fields']['anchor'] = array(
			'type' => 'text',
			'label' => __( 'Anchor ID', 'siteorigin-premium' ),
			'description' => __( 'An ID is required for the anchor to function.', 'siteorigin-premium' ),
			'state_handler' => array(
				'anchor_tags[show]' => array( 'show' ),
				'anchor_tags[hide]' => array( 'hide' ),
			),
		);

		return $form_options;
	}

	public function slider_enqueue_js( $instance ) {
		if (
			is_array( $instance ) &&
			! empty( $instance['controls'] ) &&
			! empty( $instance['controls']['use_anchor_tags'] )
		) {
			wp_enqueue_script(
				'so-premium-anchor-id-slider',
				plugin_dir_url( __FILE__ ) . 'js/anchor-id-slider' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array(
					'so-premium-anchor-id',
					'sow-slider-slider',
					'sow-slider-slider-cycle2',
				),
				SITEORIGIN_PREMIUM_VERSION
			);
		}
	}

	public function accordion_enqueue_js( $instance ) {
		if ( ! empty( $instance['use_anchor_tags'] ) ) {
			wp_enqueue_script(
				'so-premium-anchor-id-accordion',
				plugin_dir_url( __FILE__ ) . 'js/anchor-id-accordion' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array(
					'so-premium-anchor-id',
				),
				SITEORIGIN_PREMIUM_VERSION
			);
		}
	}
	public function tabs_enqueue_js( $instance ) {
		if ( ! empty( $instance['use_anchor_tags'] ) ) {
			wp_enqueue_script(
				'so-premium-anchor-id-tabs',
				plugin_dir_url( __FILE__ ) . 'js/anchor-id-tabs' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array(
					'so-premium-anchor-id',
				),
				SITEORIGIN_PREMIUM_VERSION
			);
		}
	}

	public function anything_carousel_enqueue_js( $frontend_scripts ) {
		return array_merge(
			array(
				'so-premium-anything-carousel' => array(
					'so-premium-anything-carousel',
					plugin_dir_url( __FILE__ ) . 'js/anchor-id-anything-carousel' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
					array(
						'jquery',
						'so-premium-anchor-id',
					),
					SITEORIGIN_PREMIUM_VERSION
				),
			),
			$frontend_scripts
		);
	}

	public function add_accordion_tabs_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$position = array_key_exists( 'design', $form_options ) ? 'design' : count( $form_options );
		siteorigin_widgets_array_insert(
			$form_options,
			$position,
			array(
			'use_anchor_tags' => array(
				'type' => 'checkbox',
				'label' => __( 'Add anchor ID in the URL', 'siteorigin-premium' ),
				'state_emitter' => array(
					'callback' => 'conditional',
					'args' => array(
						'anchor_tags[show]: val',
						'anchor_tags[hide]: ! val',
					),
				),
			),
			'anchor_id' => array(
				'type' => 'text',
				'label' => __( 'Anchor ID', 'siteorigin-premium' ),
				'description' => __( 'Set a custom anchor ID.', 'siteorigin-premium' ),
					'state_handler' => array(
						'anchor_tags[show]' => array( 'show' ),
						'anchor_tags[hide]' => array( 'hide' ),
					),
				),
			)
		);

		return $form_options;
	}

	/**
	 * Filters additional front end parameters.
	 *
	 * @param $params array The front end parameters
	 * @param $instance array The widget instance.
	 * @param $widget SiteOrigin_Widget_Tabs_Widget The widget object.
	 *
	 * @return mixed The modified front end parameters array.
	 */
	public function add_accordion_tabs_front_end_params( $params, $instance, $widget ) {
		if ( ! empty( $instance['use_anchor_tags'] ) ) {
			if ( ! empty( $instance['anchor_id'] ) ) {
				$params['anchor-id'] = sanitize_title_with_dashes( $instance['anchor_id'] );
			}
		}

		return $params;
	}

	public function add_anything_carousel_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$form_options['carousel_settings']['fields']['use_anchor_tags'] = array(
			'type' => 'checkbox',
			'label' => __( 'Add item anchor id in the URL', 'siteorigin-premium' ),
			'state_emitter' => array(
				'callback' => 'conditional',
				'args' => array(
					'anchor_tags[show]: val',
					'anchor_tags[hide]: ! val',
				),
			),
		);

		$form_options['carousel_settings']['fields']['anchor'] = array(
			'type' => 'text',
			'label' => __( 'Carousel anchor', 'siteorigin-premium' ),
			'description' => __( 'Optionally set a custom anchor id. Required if the widget title field is empty.', 'siteorigin-premium' ),
			'state_handler' => array(
				'anchor_tags[show]' => array( 'show' ),
				'anchor_tags[hide]' => array( 'hide' ),
			),
		);

		return $form_options;
	}

	public function anything_carousel_add_anchor( $template_vars, $instance ) {
		if (
			! empty( $template_vars['settings']['attributes'] ) &&
			! empty( $instance['carousel_settings']['use_anchor_tags'] ) &&
			(
				! empty( $instance['carousel_settings']['anchor'] ) ||
				! empty( $instance['title'] )
			)
		) {
			$template_vars['settings']['attributes']['anchor-id'] = sanitize_title_with_dashes(
				! empty( $instance['carousel_settings']['anchor'] ) ? $instance['carousel_settings']['anchor'] : $instance['title']
			);
		}

		return $template_vars;
	}
}
