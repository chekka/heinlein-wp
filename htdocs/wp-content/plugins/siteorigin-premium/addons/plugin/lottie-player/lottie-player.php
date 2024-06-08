<?php
/*
Plugin Name: SiteOrigin Lottie Player
Description: Bring your ideas to life with Lottie Animations, offering adjustable loops, speeds, and interactive triggers for captivating and dynamic content.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/lottie-player/
Tags: Widgets Bundle
Video:
Requires: so-widgets-bundle/lottie-player
*/

class SiteOrigin_Premium_Plugin_Lottie_Player {
	public function __construct() {
		add_filter( 'siteorigin_widgets_form_options_sow-lottie-player', array( $this, 'add_form_options' ) );
		add_filter( 'siteorigin_widgets_template_variables_sow-lottie-player', array( $this, 'add_template_vars' ), 10, 2 );
		add_action( 'siteorigin_widgets_enqueue_frontend_scripts_sow-lottie-player', array( $this, 'enqueue_js' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_form_options( $form_options ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		// There are situations where we need to hide the autoplay field.
		// Certain animation modes really need for autoplay to be enabled to
		// function correctly.
		$form_options['autoplay']['state_handler'] = array(
			'animation_mode[default,bounce]' => array( 'show' ),
			'_else[animation_mode]' => array( 'hide' ),
		);

		$addon_form_options = array(
			'count' => array(
				'type' => 'number',
				'label' => __( 'Count', 'siteorigin-premium' ),
				'description' => __( 'Number of times to loop the animation.', 'siteorigin-premium' ),
			),

			'direction' => array(
				'type' => 'select',
				'label' => __( 'Direction', 'siteorigin-premium' ),
				'description' => __( 'Direction of the animation.', 'siteorigin-premium' ),
				'default' => 'forward',
				'options' => array(
					'1' => __( 'Forward', 'siteorigin-premium' ),
					'-1' => __( 'Backward', 'siteorigin-premium' ),
				),
				'state_handler' => array(
					'animation_mode[default]' => array( 'show' ),
					'_else[animation_mode]' => array( 'hide' ),
				),
			),

			'speed' => array(
				'type' => 'number',
				'label' => __( 'Speed', 'siteorigin-premium' ),
				'description' => __( 'The animation speed. 1 is 100% - 0 is 0%.', 'siteorigin-premium' ),
				'default' => 1,
			),

			'animation' => array(
				'type' => 'select',
				'label' => __( 'Animation Mode', 'siteorigin-premium' ),
				'options' => array(
					'default' => __( 'Normal', 'siteorigin-premium' ),
					'bounce' => __( 'Bounce', 'siteorigin-premium' ),
					'scroll' => __( 'Scroll', 'siteorigin-premium' ),
					'cursor' => __( 'Cursor Position', 'siteorigin-premium' ),
					'click' => __( 'Click', 'siteorigin-premium' ),
					'hover' => __( 'Hover', 'siteorigin-premium' ),
					'toggle' => __( 'Toggle', 'siteorigin-premium' ),
					'hold' => __( 'Hold', 'siteorigin-premium' ),
					'pauseHold' => __( 'Toggle Hold', 'siteorigin-premium' ),
					'visibility' => __( 'Visibility', 'siteorigin-premium' ),
				),
				'state_emitter' => array(
					'callback' => 'select',
					'args' => array( 'animation_mode' ),
				),
			),

			'visibility_percentage' => array(
				'type' => 'slider',
				'label' => __( 'Visibility Percentage', 'siteorigin-premium' ),
				'description' => __( 'The percentage visible of the Lottie required before the animation will play.', 'siteorigin-premium' ),
				'min' => 0,
				'max' => 50,
				'default' => 0,
				'state_handler' => array(
					'animation_mode[visibility]' => array( 'show' ),
					'_else[animation_mode]' => array( 'hide' ),
				),
			),
		);

		return $form_options + $addon_form_options;
	}

	public function add_template_vars( $template_vars, $instance ) {
		$addon_template_vars_attributes = array(
			'count' => ! empty( $instance['count'] ) ? abs( (float) $instance['count'] ) : false,
			'direction' => ! empty( $instance['direction'] ) ? $instance['direction'] : false,
			'speed' => ! empty( $instance['speed'] ) ? abs( (float) $instance['speed'] ) : 1,
			'mode' => 'normal',
		);

		if ( ! empty( $instance['animation'] ) && $instance['animation'] != 'default' ) {
			if ( $instance['animation'] == 'hover' ) {
				$addon_template_vars_attributes['hover'] = 'true';
			} else {
				if ( $instance['animation'] == 'bounce' ) {
					$addon_template_vars_attributes['mode'] = 'bounce';
				} elseif ( $instance['animation'] == 'scroll' || $instance['animation'] == 'visibility' ) {
					$addon_template_vars_attributes['mode'] = 'scroll';
					unset( $template_vars['attributes']['autoplay'] );
				} else {
					$addon_template_vars_attributes['mode'] = 'cursor';
					unset( $template_vars['attributes']['autoplay'] );
				}
				$addon_template_vars_attributes['animation'] = $instance['animation'];
				$addon_template_vars_attributes['id'] = 'lottie-player-' . $instance['_sow_form_id'];
			}

			if ( $instance['animation'] != 'hover' && $instance['animation'] != 'bounce' ) {
				$addon_template_vars_attributes['direction'] = true;
			} else {
				unset( $addon_template_vars_attributes['direction'] );
			}

			if ( $instance['animation'] == 'visibility' ) {
				$addon_template_vars_attributes['visibility-percentage'] = ! empty( $instance['visibility_percentage'] ) ? $instance['visibility_percentage'] : 0.1;
			}
		}

		$template_vars['attributes'] = $template_vars['attributes'] + $addon_template_vars_attributes;

		return $template_vars;
	}

	public function enqueue_js( $instance ) {
		if (
			! empty( $instance['animation'] ) &&
			$instance['animation'] != 'default' &&
			$instance['animation'] != 'hover'
		) {
			wp_enqueue_script(
				'so-premium-lottie-addon',
				plugin_dir_url( __FILE__ ) . 'js/lottie-addon' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array( 'jquery' ),
				SITEORIGIN_PREMIUM_VERSION
			);

			wp_enqueue_script(
				'so-premium-lottie-interactivity',
				plugin_dir_url( __FILE__ ) . 'js/lottie-interactivity' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array(),
				SITEORIGIN_PREMIUM_VERSION
			);
		}
	}
}
