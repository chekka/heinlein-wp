<?php
/*
Plugin Name: SiteOrigin Page Background
Description: Add page specific background images with support for high-pixel-density displays.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/page-background/
Tags: Widgets Bundle
Requires: siteorigin-premium
*/

class SiteOrigin_Premium_Plugin_Page_Background {
	public $fields = array();

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init() {
		add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ) );
		add_action( 'wp_head', array( $this, 'add_page_background' ) );
		$this->fields = array(
			'background_color' => array(
				'type' => 'color',
				'label' => __( 'Background Color', 'siteorigin-premium' ),
			),

			'background' => array(
				'type' => 'media',
				'label' => __( 'Background Image', 'siteorigin-premium' ),
				'fallback' => true,
			),

			'background_display' => array(
				'type' => 'select',
				'label' => __( 'Background Image Display', 'siteorigin-premium' ),
				'default' => 'cover',
				'options' => array(
					'tile'     => __( 'Tiled Image', 'siteorigin-premium' ),
					'cover'    => __( 'Cover', 'siteorigin-premium' ),
					'center'   => __( 'Centered, with original size', 'siteorigin-premium' ),
					'contain'  => __( 'Contain', 'siteorigin-premium' ),
					'fixed'    => __( 'Fixed', 'siteorigin-premium' ),
					'parallax' => __( 'Parallax', 'siteorigin-premium' ),
				),
				'state_emitter' => array(
					'callback' => 'select',
					'args' => array( 'page_background_display' ),
				),
			),
		);


		if ( SiteOrigin_Premium::single()->is_addon_active( 'plugin/retina-background-images' ) ) {
			$this->fields['retina_background'] = array(
				'type' => 'media',
				'label' => __( 'Retina Background Image', 'siteorigin-premium' ),
				'fallback' => true,
				'state_handler' => array(
					'page_background_display[parallax]' => array( 'hide' ),
					'_else[page_background_display]' => array( 'show' ),
				),
			);
		}
	}

	public function get_settings_form() {
		$this->fields['selector'] = array(
			'type' => 'text',
			'label' => __( 'Selector', 'siteorigin-premium' ),
			'default' => self::get_selector(),
			'description' => __( 'The selector used when applying a background. The default is typically fine but certain themes may require a different selector.', 'siteorigin-premium' ),
		);

		return new SiteOrigin_Premium_Form(
			'so-addon-page-background-settings',
			$this->fields
		);
	}

	public function metabox_options( $form_options ) {
		$global_settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/page-background', false );
		$this->fields['background_display']['default'] = ! empty( $global_settings['background_display'] ) ? $global_settings['background_display'] : 'cover';
		return $form_options + array(
			'page_background' => array(
				'type' => 'section',
				'label' => __( 'Page Background' , 'siteorigin-premium' ),
				'tab' => true,
				'hide' => true,
				'fields' => $this->fields,
			),
		);
	}

	public static function get_selector( $selector = 'body' ) {
		if ( get_stylesheet() == 'vantage' ) {
			$selector = '#main.site-main';
		}

		return apply_filters( 'siteorigin_premium_page_background_selector', $selector );
	}

	public function add_page_background() {
		$premium_meta = get_post_meta( get_the_ID(), 'siteorigin_premium_meta', true );
		$global_settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/page-background', false );
		if ( ! empty( $global_settings ) ) {
			if ( empty( $premium_meta['page_background'] ) ) {
				$settings = $global_settings;
			} else {
				$settings = array();
				$fields = array(
					'background_color',
					'background',
					'background_fallback',
					'background_display',
				);

	 			if ( SiteOrigin_Premium::single()->is_addon_active( 'plugin/retina-background-images' ) ) {
	 				$fields[] = 'retina_background';
	 				$fields[] = 'retina_background_fallback';
	 			}

				// Override global settings as needed.
 				$fields = array_flip( $fields );
				foreach ( $fields as $k => $v ) {
					if ( empty( $premium_meta['page_background'][ $k ] ) ) {
						if ( ! empty( $global_settings[ $k ] ) ) {
							$settings[ $k ] = $global_settings[ $k ];
						}
					} else {
						$settings[ $k ] = $premium_meta['page_background'][ $k ];
					}
				}
			}
		} elseif ( ! empty( $premium_meta['page_background'] ) ) {
			$settings = $premium_meta['page_background'];
		}

		if ( ! empty( $settings ) ) {
			$css = null;

			if ( ! empty( $settings['background'] ) || ! empty( $settings['background_fallback'] ) ) {
				$background = self::get_background(
					! empty( $settings['background'] ) ? $settings['background'] : false,
					'full',
					! empty( $settings['background_fallback'] ) ? $settings['background_fallback'] : false
				);
				if ( ! empty( $background ) ) {
					if (
						! empty( $settings['background_display'] ) &&
						$settings['background_display'] == 'parallax'
					) {
						wp_enqueue_script( 'siteorigin-parallax' );
						wp_enqueue_script( 'simpleParallax' );
						?>
						<img
							class="siteorigin-premium-page-background-parallax"
							src="<?php echo esc_url( $background[0] ); ?>"
							data-siteorigin-parallax="true"
							loading="eager"
							style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index: 0;"
						>
						<?php
					} else {
						if (
							! empty( $settings['retina_background'] ) ||
							! empty( $settings['retina_background_fallback'] )
						) {
							$retina_background = self::get_background(
								! empty( $settings['retina_background'] ) ? $settings['retina_background'] : false,
								'full',
								! empty( $settings['retina_background_fallback'] ) ? $settings['retina_background_fallback'] : false
							);

							if ( ! empty( $retina_background ) ) {
								$css .= 'background-image: image-set(url("' . $background[0] . '") 1x,
								url("' . $retina_background[0] . '") 2x);';
							}
						}

						// Retina background not set, set normal background.
						if ( empty( $css ) ) {
							$css .= 'background: url(' . esc_url( $background[0] ) .');';
						}

						switch ( $settings['background_display'] ) {
							case 'tile':
								$css .= 'background-repeat: repeat !important;';
								break;
							case 'default':
							case 'cover':
								$css .= 'background-position: center center !important;';
								$css .= 'background-size: cover !important;';
								break;
							case 'contain':
								$css .= 'background-size: contain !important;';
								break;
							case 'parallax':
							case 'center':
								$css .= 'background-position: center center !important;';
								$css .= 'background-repeat: no-repeat !important;';
								break;
							case 'fixed':
								$css .= 'background-attachment: fixed !important;';
								$css .= 'background-position: center center !important;';
								$css .= 'background-size: cover !important;';
								break;
						}
					}
				}

				if ( ! empty( $settings['background_color'] ) ) {
					$css .= 'background-color: ' . esc_attr( $settings['background_color'] ) . ' !important;';
				}
			}

			if ( ! empty( $settings['background_color'] ) && empty( $css ) ) {
				$css .= 'background: ' . esc_attr( $settings['background_color'] ) . ' !important;';
			}

			if ( ! empty( $css ) ) {
				$selector = ! empty( $global_settings['selector'] ) ? $global_settings['selector'] : self::get_selector();
				$css = apply_filters( 'siteorigin_premium_page_background_css', $css );
				echo '<style>' . trim( strip_tags( "$selector { $css }" ) ) . '</style>';
			}
		}
	}

	// Based on `siteorigin_widgets_get_attachment_image_src` function in the Siteorigin Widgets Bundle.
	private static function get_background( $attachment, $size, $fallback = false, $fallback_size = array() ) {
		if ( empty( $attachment ) && ! empty( $fallback ) ) {
			if ( ! empty( $fallback_size ) ) {
				extract( $fallback_size );
			} else {
				$url = parse_url( $fallback );

				if (
					! empty( $url['fragment'] ) &&
					preg_match(
						'/^([0-9]+)x([0-9]+)$/',
						$url['fragment'],
						$matches
					) ) {
					$width = (int) $matches[1];
					$height = (int) $matches[2];
				} else {
					$width = 0;
					$height = 0;
				}
			}

			// TODO, try get better values than 0 for width and height.
			return array( $fallback, $width, $height, false );
		}

		if ( ! empty( $attachment ) ) {
			return wp_get_attachment_image_src( $attachment, $size );
		}

		return false;
	}
}
