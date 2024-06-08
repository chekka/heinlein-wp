<?php
/*
Plugin Name: SiteOrigin Parallax Sliders
Description: Enhance your Slider, Hero, and Layout Slider widgets with parallax and fixed backgrounds, making your site's content more engaging and visually appealing.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/parallax-sliders/
Tags: Widgets Bundle
Video: 314963213
Requires: so-widgets-bundle/slider, so-widgets-bundle/layout-slider, so-widgets-bundle/hero
*/

class SiteOrigin_Premium_Plugin_Parallax_Sliders {
	public function __construct() {
		add_filter( 'siteorigin_widgets_form_options_sow-slider', array( $this, 'widget_forms' ), 10, 2 );
		add_filter( 'siteorigin_widgets_form_options_sow-hero', array( $this, 'widget_forms' ), 10, 2 );
		add_filter( 'siteorigin_widgets_form_options_sow-layout-slider', array( $this, 'widget_forms' ), 10, 2 );

		add_filter( 'siteorigin_widgets_slider_wrapper_attributes', array( $this, 'slider_wrapper_attributes' ), 10, 3 );
		add_filter( 'siteorigin_widgets_slider_overlay_attributes', array( $this, 'slider_overlay_attributes' ), 10, 3 );

		if ( SiteOrigin_Premium::single()->use_new_parallax() ) {
			add_action( 'siteorigin_widgets_slider_before_contents', array( $this, 'add_new_parallax_image' ) );
		}

		add_filter( 'siteorigin_widgets_less_sow-hero', array( $this, 'add_slider_less' ), 10, 3 );
		add_filter( 'siteorigin_widgets_less_sow-layout-slider', array( $this, 'add_slider_less' ), 10, 3 );

		add_filter( 'siteorigin_premium_parallax_type', array( $this, 'legacy_fitler_use_new_parallax' ) );
		add_filter( 'siteorigin_premium_parallax_fallback_settings', array( $this, 'legacy_fitler_fallback_settings' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function slider_wrapper_attributes( $attributes, $frame, $background ) {
		if ( empty( $background['image'] ) || ! isset( $background['image-sizing'] ) || $background['image-sizing'] == 'cover' ) {
			return $attributes;
		}

		if ( isset( $background['opacity'] ) && $background['opacity'] != 1 ) {
			return $attributes;
		}

		if ( $background['image-sizing'] == 'parallax' ) {
			if ( SiteOrigin_Premium::single()->use_new_parallax() ) {
				$attributes['style'] = array();
			} else {
				if ( empty( $background['image-width'] ) || empty( $background['image-height'] ) ) {
					return $attributes;
				}
				$attributes['style'] = array();

				$attributes['data-siteorigin-parallax'] = json_encode( array(
					'backgroundUrl' => $background['image'],
					'backgroundSize' => array(
						$background['image-width'],
						$background['image-height'],
					),
					'backgroundSizing' => 'scaled',
				) );
				wp_enqueue_script( 'siteorigin-parallax' );
			}
		} elseif ( $background['image-sizing'] == 'fixed' ) {
			$attributes['style'][] = 'background-size: cover';
			$attributes['style'][] = 'background-attachment: fixed';
		}

		return $attributes;
	}

	public function slider_overlay_attributes( $attributes, $frame, $background ) {
		if ( empty( $background['image'] ) || ! isset( $background['opacity'] ) || $background['opacity'] == 1 ) {
			return $attributes;
		}

		if ( ! isset( $background['image-sizing'] ) || $background['image-sizing'] == 'cover' ) {
			return $attributes;
		}

		if ( $background['image-sizing'] == 'parallax' ) {
			if ( SiteOrigin_Premium::single()->use_new_parallax() ) {
				unset( $attributes['style'] );
			} else {
				if ( empty( $background['image-width'] ) || empty( $background['image-height'] ) ) {
					return $attributes;
				}
				// Remove the background iamge.
				if ( strpos( $attributes['style'][0], 'background-image' ) !== 0 ) {
					unset( $attributes['style'][0] );
				}

				$attributes['data-siteorigin-parallax'] = json_encode( array(
					'backgroundUrl' => $background['image'],
					'backgroundSize' => array(
						$background['image-width'],
						$background['image-height'],
					),
					'backgroundSizing' => 'scaled',
				) );
				wp_enqueue_script( 'siteorigin-parallax' );
			}
		} elseif ( $background['image-sizing'] == 'fixed' ) {
			$attributes['style'][] = 'background-size: cover';
			$attributes['style'][] = 'background-attachment: fixed';
		}

		return $attributes;
	}

	public function widget_forms( $form, $widget ) {
		switch( get_class( $widget ) ) {
			case 'SiteOrigin_Widget_Hero_Widget':
			case 'SiteOrigin_Widget_LayoutSlider_Widget':
				if ( isset( $form['frames']['fields']['background']['fields']['image_type']['options'] ) ) {
					$form['frames']['fields']['background']['fields']['image_type']['options']['parallax'] = __( 'Parallax', 'siteorigin-premium' );
					$form['frames']['fields']['background']['fields']['image_type']['options']['fixed'] = __( 'Fixed', 'siteorigin-premium' );
				}
				break;

			case 'SiteOrigin_Widget_Slider_Widget':
				if ( isset( $form['frames']['fields']['background_image_type']['options'] ) ) {
					$form['frames']['fields']['background_image_type']['options']['parallax'] = __( 'Parallax', 'siteorigin-premium' );
				}
				break;
		}

		return $form;
	}

	/**
	 * Prevent Photon from overriding parallax images when it calculates srcset and filters the_content.
	 *
	 * @return bool
	 */
	public function jetpack_photon_exclude_modern_parallax( $skip, $src, $tag ) {
		if ( ! is_array( $tag ) && strpos( $tag, 'data-siteorigin-parallax' ) !== false ) {
			$skip = true;
		}

		return $skip;
	}

	/**
	 * Prevent Photon from filtering srcset.
	 * This is done using a method to prevent conflicting with other usage of this filter.
	 *
	 * @return false
	 */
	public function jetpack_photon_exclude_parallax_srcset( $valid, $url, $parsed_url ) {
		return false;
	}

	public function add_new_parallax_image( $frame ) {
		// Is this the Slider widget without a foreground image?
		if ( ! empty( $frame['background_image_type'] ) && $frame['background_image_type'] == 'parallax' ) {
			// If the slider doesn't have a foreground, just enqueue simpleParallax.
			if ( empty( $frame['foreground_image'] ) && empty( $frame['foreground_image_fallback'] ) ) {
				wp_enqueue_script( 'simpleParallax' );
				if ( ! wp_script_is( 'siteorigin-panels-front-styles', 'registered' ) ) {
					wp_enqueue_script( 'siteorigin-parallax' );
				}

				return;
			}
			$url_field = $frame['background_image'];
			$url_fallback_field = $frame['background_image_fallback'];
			$opacity = 1;
		} elseif ( isset( $frame['background'] ) && $frame['background']['image_type'] == 'parallax' ) {
			$url_field = $frame['background']['image'];
			$url_fallback_field = $frame['background']['image_fallback'];
			$opacity = (int) $frame['background']['opacity'] / 100;
		} else {
			return;
		}

		// Jetpack Image Accelerator (Photon) can result in the parallax being incorrectly sized so we need to exclude it.
		$photon_exclude = class_exists( 'Jetpack_Photon' ) && Jetpack::is_module_active( 'photon' );

		if ( $photon_exclude ) {
			add_filter( 'photon_validate_image_url', array( $this, 'jetpack_photon_exclude_parallax_srcset' ), 10, 3 );
			// Prevent Photon from overriding the image URL later.
			add_filter( 'jetpack_photon_skip_image', array( $this, 'jetpack_photon_exclude_modern_parallax' ), 10, 3 );
		}

		$parallax = false;

		$image_html = wp_get_attachment_image(
			$url_field,
			'full',
			false,
			apply_filters(
				'siteorigin_widgets_slider_attr',
				array(
					'data-siteorigin-parallax' => 'true',
					'loading' => 'eager',
					'style' => "opacity: $opacity",
				)
			)
		);

		if ( ! empty( $image_html ) ) {
			$parallax = true;
			echo $image_html;
		} elseif ( ! empty( $url_fallback_field ) ) {
			$parallax = true;
			echo '<img src="' . esc_url( $url_fallback_field ) . '" data-siteorigin-parallax="true" style="opacity: ' . $opacity . '">';
		}

		if ( $photon_exclude ) {
			// Restore Photon.
			remove_filter( 'jetpack_photon_override_image_downsize', array( $this, 'jetpack_photon_exclude_parallax_srcset' ), 10 );
		}

		if ( $parallax ) {
			wp_enqueue_script( 'simpleParallax' );
			if ( ! wp_script_is( 'siteorigin-panels-front-styles', 'registered' ) ) {
				wp_enqueue_script( 'siteorigin-parallax' );
			}
		}
	}

	public function add_slider_less( $less, $instance ) {
		// Disable fixed Sliders on mobile devices due to an issue on iOS.
		$less .= '
		@media (max-width: @responsive_breakpoint) {
			.sow-slider-image-fixed {
				background-attachment: scroll !important;
			}
		}';

		// The new parallax needs some CSS added to ensure the image displays correctly.
		if ( SiteOrigin_Premium::single()->use_new_parallax() ) {
			$less .= '
			.simpleParallax > img {
				bottom: 0;
				left: 0;
				position: absolute;
				right: 0;
				top: 0;
				width: 100%;
				z-index: 0;
			}';
		}

		return $less;
	}

	public function legacy_fitler_use_new_parallax( $parallax_status ) {
		return apply_filters( 'siteorigin_parallax_sliders_use_new_parallax', $parallax_status );
	}

	public function legacy_fitler_fallback_settings( $settings ) {
		return apply_filters( 'siteorigin_parallax_sliders_fallback_settings', $settings );
	}
}
