<?php
/*
Plugin Name: SiteOrigin Web Font Selector
Description: Choose from hundreds of beautiful web fonts right in the visual editor.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/web-font-selector/
Tags: CSS, Widgets Bundle
Video: 314963142
Requires: so-css, so-widgets-bundle/editor
*/

class SiteOrigin_Premium_Plugin_Web_Font_Selector {

	public function __construct() {
		add_filter( 'siteorigin_css_property_controllers', array( $this, 'modify_font_controls' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_socss_control_scripts' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ) );
		add_action( 'wp_enqueue_editor', array( $this, 'enqueue_tinymce_plugin_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_tinymce_plugin_scripts' ) );
		add_filter( 'widget_display_callback', array( $this, 'check_widgets_for_usage' ), 10, 3 );
		add_filter( 'mce_external_plugins', array( $this, 'add_font_selector_tinymce_plugin' ), 15 );
		add_filter( 'mce_buttons', array( $this, 'add_font_selector_tinymce_button' ), 15 );
		// Specifically for Widgets Bundle previews.
		add_action( 'siteorigin_widgets_render_preview_sow-editor', array( $this, 'enqueue_front_end_scripts' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public static function get_font_modules() {
		static $font_modules;

		if ( empty( $font_modules ) ) {
			$font_modules = include __DIR__ . '/fonts/font_modules.php';
		}

		static $fonts;

		if ( empty( $fonts ) ) {
			foreach ( $font_modules as $module_name => $module ) {
				$module['fonts'] = include __DIR__ . '/fonts/' . $module_name . '.php';
				$fonts[ $module_name ] = $module;
			}
		}

		return $fonts;
	}

	public function modify_font_controls( $controls ) {
		$fonts = self::get_font_modules();

		$ctrls = $controls['text']['controllers'];

		foreach ( $ctrls as $key => $ctrl ) {
			if ( $ctrl['type'] == 'font_select' ) {
				$ctrl['args']['modules'] = $fonts;
				$ctrls[ $key ] = $ctrl;
			}
		}
		$controls['text']['controllers'] = $ctrls;

		return $controls;
	}

	public function add_font_selector_tinymce_plugin( $plugins ) {
		$plugins['so-premium-font-selector'] = plugin_dir_url( __FILE__ ) . 'js/so-premium-tmce-fonts-plugin' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js';

		return $plugins;
	}

	public function add_font_selector_tinymce_button( $buttons ) {
		$buttons[] = 'so-premium-font-selector';

		return $buttons;
	}

	public function enqueue_tinymce_plugin_scripts() {
		$this->enqueue_front_end_scripts();

		if (
			is_admin() ||
			(
				class_exists( 'FLBuilderModel' ) &&
				FLBuilderModel::is_builder_enabled()
			)
		) {
			$this->enqueue_admin_scripts();
		}

		wp_enqueue_script(
			'so-premium-font-selector',
			plugin_dir_url( __FILE__ ) . 'js/so-premium-tmce-fonts-plugin' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION,
			true
		);

		wp_localize_script(
			'siteorigin-premium-tinymce-font-selector-plugin',
			'soPremiumFonts',
			array(
				'font_modules' => self::get_font_modules(),
				'placeholder_text' => __( 'Select Web Font', 'siteorigin-premium' ),
			)
		);
	}

	public function enqueue_socss_control_scripts( $page ) {
		if ( $page != 'appearance_page_so_custom_css' ) {
			return;
		}

		$this->enqueue_admin_scripts();

		wp_enqueue_script(
			'font-select-control',
			plugin_dir_url( __FILE__ ) . 'js/font-select-control' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'siteorigin-custom-css' ),
			SITEORIGIN_PREMIUM_VERSION,
			true
		);
	}

	private function enqueue_admin_scripts() {
		// We'll use chosen for the font selector
		wp_enqueue_script(
			'siteorigin-premium-chosen',
			plugin_dir_url( __FILE__ ) . 'js/lib/chosen/chosen.jquery' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery' ),
			'1.4.2'
		);
		wp_enqueue_style(
			'siteorigin-premium-chosen',
			plugin_dir_url( __FILE__ ) . 'js/lib/chosen/chosen' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.css',
			array(),
			'1.4.2'
		);

		wp_enqueue_style(
			'so-premium-tinymce-chosen',
			plugin_dir_url( __FILE__ ) . 'js/so-premium-tinymce-chosen.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_enqueue_script(
			'web-font-loader',
			'//ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js',
			array(),
			null,
			true
		);

		wp_enqueue_script(
			'siteorigin-web-font-selector',
			plugin_dir_url( __FILE__ ) . 'js/web-font-selector' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery', 'siteorigin-premium-chosen' ),
			SITEORIGIN_PREMIUM_VERSION,
			true
		);

		wp_localize_script(
			'siteorigin-web-font-selector',
			'soPremiumFontsWebFontSelector',
			array(
				'variant' => __( 'Variant', 'siteorigin-premium' ),
			)
		);
	}

	public function enqueue_front_end_scripts() {
		if ( is_admin() || apply_filters( 'siteorigin_premium_web_font_selector_import_fonts', true ) ) {
			wp_register_script(
				'siteorigin-premium-web-font-importer',
				plugin_dir_url( __FILE__ ) . 'js/so-premium-tmce-fonts-importer' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
				array(),
				SITEORIGIN_PREMIUM_VERSION,
				true
			);

			wp_localize_script(
				'siteorigin-premium-web-font-importer',
				'soPremiumFonts',
				array(
					'font_modules' => self::get_font_modules(),
				)
			);

			// Check the current page if we need to enqueue the web font importer now.
			$post = get_post( get_the_ID() );

			if (
				is_admin() ||
				apply_filters( 'siteorigin_premium_web_font_selector_frontend_force_load', false, $post ) ||
				(
					! empty( $post ) &&
					strpos( $post->post_content, 'so-premium-web-font' )
				) ||
				(
					class_exists( 'FLBuilderModel' ) &&
					FLBuilderModel::is_builder_enabled()
				)
			) {
				wp_enqueue_script( 'siteorigin-premium-web-font-importer' );
			}
		}
	}

	/**
	 * Check field for Web Font Selector Importer. Will check nested settings.
	 */
	private function check_array_for_usage( $settings ) {
		foreach ( $settings as $setting ) {
			if ( is_array( $setting ) && $this->check_array_for_usage( $setting ) ) {
				return true;
			} elseif ( is_string( $setting ) && strpos( $setting, 'so-premium-web-font' ) ) {
				return true;
			}
		}
	}

	/**
	 * Check widget area widget $instance to see if we need to enqueue the web font selector.
	 */
	public function check_widgets_for_usage( $instance, $widget, $args ) {
		if ( ! empty( $instance ) && ! wp_script_is( 'siteorigin-premium-web-font-importer', 'enqueued' ) ) {
			if ( $this->check_array_for_usage( $instance ) ) {
				wp_enqueue_script( 'siteorigin-premium-web-font-importer' );
			}
		}

		return $instance;
	}
}
