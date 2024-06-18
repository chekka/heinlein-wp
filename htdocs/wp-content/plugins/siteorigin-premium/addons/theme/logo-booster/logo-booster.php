<?php
/*
Plugin Name: SiteOrigin Logo Booster
Description: Customize logos on a per-page or language basis, seamlessly adapting your branding to enhance appeal and relevance in diverse visitor contexts.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/theme-addons/logo-booster/
Tags: Theme, Widgets Bundle
Requires: so-widgets-bundle
*/

class SiteOrigin_Premium_Theme_Logo_booster {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init() {
		if ( function_exists( 'siteorigin_setting' ) ) {
			add_filter( 'theme_mod_custom_logo', array( $this, 'override_wp_logo_setting' ) );
			add_filter( 'get_custom_logo', array( $this, 'add_logo_wp' ), 1 );
			add_filter( 'siteorigin_north_logo_url', array( $this, 'add_logo_theme' ) );
			add_filter( 'siteorigin_north_logo_attributes', array( $this, 'setup_scroll_logo_theme' ), 20 );
			add_filter( 'siteorigin_unwind_logo_url', array( $this, 'add_logo_theme' ) );
			add_filter( 'siteorigin_unwind_logo_attributes', array( $this, 'setup_scroll_logo_theme' ), 20 );
			add_filter( 'vantage_logo_image_id', array( $this, 'add_vantage_logo' ) );
			add_filter( 'vantage_logo_image_attributes', array( $this, 'setup_scroll_logo_theme' ), 20 );

			add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ) );
			add_filter( 'siteorigin_premium_metabox_meta', array( $this, 'metabox_migrate' ), 9, 2 );
		}
	}

	public function get_settings_form() {
		$form_options = array(
			'scroll_logo' => array(
				'type' => 'media',
				'label' => __( 'Global Sticky Logo', 'siteorigin-premium' ),
				'description' => __( 'Requires an existing page or theme logo. The Global Sticky Logo replaces this initial logo.', 'siteorigin-premium' ),
				'library' => 'image',
			),
		);

		if ( function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			$default_language = apply_filters( 'wpml_default_language', null );
			// Update standard sticky logo to reference default language.
			$form_options['scroll_logo']['label'] = $languages[ $default_language ]['native_name'] . ' ' . $form_options['scroll_logo']['label'];
			unset( $languages[ $default_language ] );

			foreach ( $languages as $cc => $language ) {
				$form_options[ $cc . '_base_logo' ] = array(
					'type' => 'media',
					'label' => sprintf( __( '%s Base Logo', 'siteorigin-premium' ), $language['native_name'] ),
					'library' => 'image',
				);
				$form_options[ $cc . '_scroll_logo' ] = array(
					'type' => 'media',
					'label' => sprintf( __( '%s Global Sticky Logo', 'siteorigin-premium' ), $language['native_name'] ),
					'library' => 'image',
				);
			}
		}

		return new SiteOrigin_Premium_Form(
			'so-addon-logo-booster-settings',
			$form_options
		);
	}

	private function get_global_settings() {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'theme/logo-booster', false );

		if ( function_exists( 'icl_get_languages' ) ) {
			$current_language = apply_filters( 'wpml_current_language', null );

			if ( ! empty( $settings[ $current_language . '_base_logo' ] ) ) {
				$settings['base_logo'] = $settings[ $current_language . '_base_logo' ];
			}

			if ( ! empty( $settings[ $current_language . '_scroll_logo' ] ) ) {
				$settings['scroll_logo'] = $settings[ $current_language . '_scroll_logo' ];
			}
		}

		return $settings;
	}

	private static function get_meta( $premium_meta = array(), $is_admin = false ) {
		$post_id = function_exists( 'is_shop' ) && is_shop() ? wc_get_page_id( 'shop' ) : get_the_id();

		if ( empty( $premium_meta ) ) {
			$premium_meta = get_post_meta( $post_id, 'siteorigin_premium_meta', true );
		}

		if ( ! is_array( $premium_meta ) ) {
			$premium_meta = array();
		}

		if (
			empty( $premium_meta['logo_booster'] ) &&
			apply_filters( 'siteorigin_premium_logo_booster_meta_migrate_check', true )
		) {
			$existing_meta = get_post_meta( $post_id, 'siteorigin_premium_logo_booster', true );

			if (
				! empty( $existing_meta ) &&
				(
					! empty( $existing_meta['base'] ) ||
					! empty( $existing_meta['sticky'] )
				)
			) {
				$premium_meta['logo_booster'] = $existing_meta;

				update_post_meta( $post_id, 'siteorigin_premium_meta', $premium_meta );
				delete_post_meta( $post_id, 'siteorigin_premium_logo_booster' );
			}
		}

		if ( $is_admin ) {
			return $premium_meta;
		} else {
			return ! empty( $premium_meta['logo_booster'] ) ? $premium_meta['logo_booster'] : array();
		}
	}

	public function add_logo_wp( $html ) {
		if (
			empty( get_theme_mod( 'custom_logo' ) ) ||
			(
				is_archive() &&
				! (
					function_exists( 'is_shop' ) &&
					is_shop()
				)
			)
		) {
			return $html;
		}

		$logo_booster_meta = self::get_meta();

		if ( ! empty( $logo_booster_meta ) && ! empty( $logo_booster_meta['base'] ) ) {
			$logo_override_id = $logo_booster_meta['base'];
		} else {
			$settings = $this->get_global_settings();

			if ( ! empty( $settings['base_logo'] ) ) {
				$logo_override_id = $settings['base_logo'];
			}
		}

		if ( ! empty( $logo_override_id ) ) {
			$logo_override = wp_get_attachment_image(
				$logo_override_id,
				'full',
				false,
				array(
					'class' => 'custom-logo',
					'loading' => 'false',
					'decoding' => 'async',
					'itemprop' => 'logo',
				)
			);
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $logo_override . '</a>';
		}

		$scroll_logo_id = $this->get_scroll_logo( $logo_booster_meta );

		if ( ! empty( $scroll_logo_id ) ) {
			$scroll_logo = wp_get_attachment_image(
				$scroll_logo_id,
				'full',
				false,
				array(
					'class' => 'alt-logo-scroll',
					'loading' => 'false',
					'decoding' => 'async',
					'itemprop' => 'logo',
				)
			);

			if ( ! empty( $scroll_logo ) ) {
				$html .= '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $scroll_logo . '</a>';
				// Add Class to hide base logo before scroll.
				$html = substr_replace( $html, ' alt-logo', strrpos( $html, 'custom-logo' ) + 11, 0 );
			}
		}

		return $html;
	}

	public function add_logo_theme( $logo_id ) {
		$logo_booster_meta = self::get_meta();

		if ( ! empty( $logo_booster_meta ) && ! empty( $logo_booster_meta['base'] ) ) {
			$logo_id = $logo_booster_meta['base'];
		} else {
			$settings = $this->get_global_settings();

			if ( ! empty( $settings['base_logo'] ) ) {
				$logo_id = $settings['base_logo'];
			}
		}

		return $logo_id;
	}

	public function add_vantage_logo( $logo ) {
		if ( siteorigin_setting( 'layout_masthead' ) == 'logo-in-menu' ) {
			return $this->add_logo_theme( $logo );
		} else {
			return $logo;
		}
	}

	public function setup_scroll_logo_theme( $attrs ) {
		if ( get_stylesheet() == 'vantage' ) {
			if ( siteorigin_setting( 'layout_masthead' ) == 'logo-in-menu' ) {
				$logo = siteorigin_setting( 'logo_image' );

				if ( empty( $logo ) && function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
					$logo = get_theme_mod( 'custom_logo' );
				}
				$logo = apply_filters( 'vantage_logo_image_id', $logo );
			}
		} else {
			$logo = siteorigin_setting( 'branding_logo' ) ? siteorigin_setting( 'branding_logo' ) : get_theme_mod( 'custom_logo' );
		}

		$settings = $this->get_global_settings();

		if ( empty( $settings ) || empty( $this->get_scroll_logo() ) || empty( $logo ) ) {
			return $attrs;
		}

		if ( empty( $attrs['class'] ) ) {
			$attrs['class'] = 'custom-logo alt-logo';
		} else {
			$attrs['class'] .= ' alt-logo';
		}

		// Prevent potential situation where the themes retina logo overrides the sticky logo.
		unset( $attrs['srcset'] );

		add_action( 'siteorigin_unwind_logo_after', array( $this, 'add_scroll_logo_theme' ) );
		add_action( 'siteorigin_north_logo_after', array( $this, 'add_scroll_logo_theme' ) );
		add_action( 'vantage_logo_image', array( $this, 'add_scroll_logo_vantage' ), 5 );

		return $attrs;
	}

	private function get_scroll_logo( $logo_booster_meta = array() ) {
		if ( empty( $logo_booster_meta ) ) {
			$logo_booster_meta = self::get_meta();
		}

		$scroll_logo_id = false;

		if ( ! empty( $logo_booster_meta ) && ! empty( $logo_booster_meta['sticky'] ) ) {
			$scroll_logo_id = $logo_booster_meta['sticky'];
		} else {
			$settings = $this->get_global_settings();

			if ( ! empty( $settings['scroll_logo'] ) ) {
				$scroll_logo_id = $settings['scroll_logo'];
			}
		}

		return $scroll_logo_id;
	}

	public function add_scroll_logo_theme( $return = false ) {
		$scroll_logo_id = $this->get_scroll_logo();

		if ( ! empty( $scroll_logo_id ) ) {
			$scroll_logo = wp_get_attachment_image(
				$scroll_logo_id,
				'full',
				false,
				array(
					'class' => 'alt-logo-scroll',
					'loading' => 'false',
					'style' => apply_filters( 'siteorigin_premium_logo_booster_sticky_style', 'max-width: 75px;' ),
					'decoding' => 'async',
					'itemprop' => 'logo',
				)
			);

			$scroll_logo = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $scroll_logo . '</a>';

			if ( $return ) {
				return $scroll_logo;
			} else {
				echo $scroll_logo;
			}
		}
	}

	public function add_scroll_logo_vantage( $logo_html ) {
		$logo_html .= $this->add_scroll_logo_theme( true );

		return $logo_html;
	}

	// If the user doesn't set a Theme or WP logo, the page defined logos can't show.
	// We need to override the theme setting with a placeholder to allow for them to appear.
	public function override_wp_logo_setting( $value ) {
		if (
			! $value &&
			! siteorigin_setting( 'branding_logo' ) &&
			! siteorigin_setting( 'logo_image' )
		) {
			$value = true; // Never output, purely for override.

			// Overriding this value will prevent the site title text from appearing.
			// We need to account for that by re-adding it if a logo isn't set using meta.
			$meta = self::get_meta();

			if ( empty( $meta ) || empty( $meta['base'] ) ) {
				if ( ! empty( $meta['sticky'] ) ) {
					add_action( 'siteorigin_unwind_logo_before', array( $this, 'add_site_title_text' ) );
					add_action( 'siteorigin_corp_logo_before', array( $this, 'add_site_title_text' ) );
				} else {
					// No meta set to display logo, let's remove the placeholder.
					$value = '';
				}
			}
		}

		return $value;
	}

	public function add_site_title_text() {
		$tag = is_front_page() ? 'h1' : 'p';
		?>
		<<?php echo esc_html( $tag ); ?> class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php bloginfo( 'name' ); ?>
			</a>
		</<?php echo esc_html( $tag ); ?>>
		<?php
	}

	public function metabox_options( $form_options ) {
		return $form_options + array(
			'logo_booster' => array(
				'type' => 'section',
				'label' => __( 'Logo Booster', 'siteorigin-premium' ),
				'tab' => true,
				'hide' => true,
				'fields' => array(
					'base' => array(
						'type' => 'media',
						'label' => __( 'Logo', 'siteorigin-premium' ),
					),

					'sticky' => array(
						'type' => 'media',
						'label' => __( 'Sticky Logo', 'siteorigin-premium' ),
					),
				),
			),
		);
	}

	public function metabox_migrate( $meta, $post ) {
		return $this->get_meta( $meta, true );
	}
}
