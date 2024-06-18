<?php
/*
Plugin Name: Embed Blocker
Description: Effortlessly comply with GDPR and DSGVO by controlling embeds from platforms like YouTube, X, Google Maps, and others, with customizable block messages.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/embed-blocker
Tags: Widgets Bundle
*/

class SiteOrigin_Premium_Plugin_Embed_Blocker {
	private $block_message;
	private $content;
	private $currentSite;
	private $blockId = 0;

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init() {
		if ( defined( 'SOW_BUNDLE_VERSION' ) ) {
			add_action( 'wp_head', array( $this, 'add_blocker_less' ) );
			add_filter( 'the_content', array( $this, 'process_content' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
			add_filter( 'siteorigin_premium_content_blocker_content', array( $this, 'additional_blocks' ), 10, 3 );

			add_action( 'siteorigin_premium_version_update', array( $this, 'update_settings_migration' ), 20, 2 );
		}
	}

	public function update_settings_migration( $new_version, $old_version ) {
		if ( version_compare( $old_version, '1.60.0', '<=' ) ) {
			$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/embed-blocker' );

			// Migrate padding to multi-measurement.
			if (
				! empty( $settings['blocker_design']['container']['padding'] )
				&& strpos( $settings['blocker_design']['container']['padding'], ' ' ) === false
			) {
				$padding = $settings['blocker_design']['container']['padding'];
				$settings['blocker_design']['container']['padding'] = sanitize_text_field( "$padding $padding $padding $padding" );
				SiteOrigin_Premium_Options::single()->save_settings( 'plugin/embed-blocker', $settings );
			}
		}
	}

	public function message_field_help( $shortcode, $text ) {
		return sprintf(
			'<li><strong>[%s]</strong> - %s</li>',
			$shortcode,
			$text
		);
	}

	public function default_block_message() {
		return apply_filters(
			'siteorigin_premium_content_blocker_message',
			'<p style="text-align: center;">' . sprintf( __( "[site]'s content is blocked.
			To view this content, %syou must agree to their privacy policy%s. <br>
			[button]", 'siteorigin-premium' ), '<a href="[privacy_link]" target="_blank" rel="noopener noreferrer">', '</a>' ) . '</p>'
		);
	}

	public function get_settings_form() {
		$message_fields = array();

		if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
			// Let's give the user the option of using PB or TinyMCE.
			$message_fields['message_type'] = array(
				'type' => 'radio',
				'label' => __( 'Message Type', 'siteorigin-premium' ),
				'default' => 'tinymce',
				'options' => array(
					'tinymce' => __( 'TinyMCE', 'siteorigin-premium' ),
					'builder' => __( 'Layout Builder', 'siteorigin-premium' ),
				),
				'state_emitter' => array(
					'callback' => 'select',
					'args' => array( 'message_type' ),
				),
			);
			$message_fields['builder'] = array(
				'type' => 'builder',
				'label' => __( 'Block Message', 'siteorigin-premium' ),
				'state_handler' => array(
					'message_type[builder]' => array( 'show' ),
					'_else[message_type]' => array( 'hide' ),
				),
			);
		}

		$message_fields['tinymce'] = array(
			'type' => 'tinymce',
			'label' => __( 'Block Message', 'siteorigin-premium' ),
			'default' => $this->default_block_message(),
			// The below doesn't do anything if PB isn't active.
			'state_handler' => array(
				'message_type[tinymce]' => array( 'show' ),
				'_else[message_type]' => array( 'hide' ),
			),
		);

		$message_fields['help'] = array(
			'type' => 'html',
			'markup' => __( 'The Block Message will be appended to the blocked content. The following placeholders can be used:', 'siteorigin-premium' ) .
				'<ul>' .
					$this->message_field_help( 'site', __( 'The label for the site that is being blocked.', 'siteorigin-premium' ) ) .
					$this->message_field_help( 'privacy_link', __( "The site's privacy policy link.", 'siteorigin-premium' ) ) .
					$this->message_field_help( 'button', __( 'The button for the user to consent to view the content.', 'siteorigin-premium' ) ) .
				'</ul>',
		);

		return new SiteOrigin_Premium_Form(
			'so-addon-embed-blocker-settings',
			array(
				'content' => array(
					'type' => 'repeater',
					'item_name' => __( 'Site', 'siteorigin-premium' ),
					'item_label' => array(
						'table' => true,
						'selectorArray' => array(
							array(
								'selector' => '.siteorigin-widget-field-label .siteorigin-widget-input',
								'valueMethod' => 'val',
								'label' => __( 'Site', 'siteorigin-premium' ),
							),
							array(
								'selector' => '.siteorigin-widget-field-urls .siteorigin-widget-input',
								'valueMethod' => 'val',
								'label' => __( 'URLs', 'siteorigin-premium' ),
							),
							array(
								'selector' => '.siteorigin-widget-field-status .siteorigin-widget-input',
								'valueMethod' => 'checkboxFormField',
								'label' => __( 'Status', 'siteorigin-premium' ),
							),
						),
					),
					'default' => array(
						array(
							'status' => false,
							'label' => 'Google Maps Embed',
							'urls' => 'maps.google.com',
							'privacy_link' => 'https://policies.google.com/privacy',
							'type' => 'iframe',
						),
						array(
							'status' => false,
							'label' => 'Facebook iFrame',
							'urls' => 'facebook.com',
							'privacy_link' => 'https://www.facebook.com/policy.php',
							'type' => 'iframe',
						),
						array(
							'status' => false,
							'label' => 'Facebook JavaScript SDK',
							'urls' => 'connect.facebook.net',
							'privacy_link' => 'https://www.facebook.com/policy.php',
							'type' => 'script',
						),
						array(
							'status' => false,
							'label' => 'Instagram',
							'urls' => 'instagram.com',
							'privacy_link' => 'https://privacycenter.instagram.com/policy/',
							'type' => 'blockquote',
						),
						array(
							'status' => false,
							'label' => 'Reddit',
							'urls' => 'reddit.com',
							'privacy_link' => 'https://www.redditinc.com/policies/privacy-policy',
							'type' => 'blockquote',
						),
						array(
							'status' => false,
							'label' => 'SoundCloud',
							'urls' => 'w.soundcloud.com',
							'privacy_link' => 'https://soundcloud.com/pages/privacy',
							'type' => 'iframe',
						),
						array(
							'status' => false,
							'label' => 'Spotify',
							'urls' => 'open.spotify.com',
							'privacy_link' => 'https://www.spotify.com/us/legal/privacy-policy/',
							'type' => 'iframe',
						),
						array(
							'status' => false,
							'label' => 'TikTok',
							'urls' => 'tiktok.com',
							'privacy_link' => 'https://www.tiktok.com/legal/privacy-policy',
							'type' => 'blockquote',
						),
						array(
							'status' => false,
							'label' => 'Twitter / X',
							'urls' => 'platform.twitter.com',
							'privacy_link' => 'https://twitter.com/privacy',
							'type' => 'blockquote',
						),
						array(
							'status' => false,
							'label' => 'Vimeo',
							'urls' => 'player.vimeo.com',
							'privacy_link' => 'https://vimeo.com/privacy',
							'type' => 'iframe',
						),
						array(
							'status' => false,
							'label' => 'YouTube',
							'urls' => 'youtube.com, youtu.be, youtube-nocokie.com',
							'privacy_link' => 'https://policies.google.com/privacy',
							'type' => 'iframe',
						),
					),
					'fields' => array(
						'status' => array(
							'type' => 'checkbox',
							'label' => __( 'Enabled', 'siteorigin-premium' ),
						),
						'label' => array(
							'type' => 'text',
							'label' => __( 'Site Label', 'siteorigin-premium' ),
						),
						'urls' => array(
							'type' => 'text',
							'label' => __( 'Site URLs', 'siteorigin-premium' ),
							'description' => __( 'Enter a comma separated list of URLs to block.', 'siteorigin-premium' ),
						),
						'privacy_link' => array(
							'type' => 'text',
							'label' => __( 'Privacy Policy Link', 'siteorigin-premium' ),
						),
						'type' => array(
							'type' => 'radio',
							'label' => __( 'Embed Type', 'siteorigin-premium' ),
							'default' => 'iframe',
							'options' => array(
								'iframe' => __( 'iFrame', 'siteorigin-premium' ),
								'blockquote' => __( 'Blockquote', 'siteorigin-premium' ),
								'script' => __( 'Script', 'siteorigin-premium' ),
							),
							'description' => __( 'Select the embed type to be targetted for blocking.', 'siteorigin-premium' ),
						),
					),
				),
				'message' => array(
					'type' => 'section',
					'label' => __( 'Message', 'siteorigin-premium' ),
					'hide' => true,
					'fields' => $message_fields,
				),
				'blocker_design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'siteorigin-premium' ),
					'hide' => true,
					'fields' => array(
						'button' => array(
							'type' => 'widget',
							'label' => __( 'Button', 'siteorigin-premium' ),
							'hide' => true,
							'class' => 'SiteOrigin_Widget_Button_Widget',
							'form_filter' => array( $this, 'filter_button_widget' ),
						),
						'container' => array(
							'type' => 'section',
							'label' => __( 'Container', 'siteorigin-premium' ),
							'fields' => array(
								'background' => array(
									'type' => 'color',
									'label' => __( 'Background Color', 'siteorigin-premium' ),
									'default' => 'rgba(0, 0, 0, 0.85)',
									'alpha' => true,
								),
								'background_image' => array(
									'type' => 'media',
									'label' => __( 'Background Image', 'siteorigin-premium' ),
								),
								'border_color' => array(
									'type' => 'color',
									'label' => __( 'Border Color', 'siteorigin-premium' ),
								),
								'border_radius' => array(
									'type' => 'measurement',
									'label' => __( 'Border Radius', 'siteorigin-premium' ),
									'default' => '3px',
								),
								'padding' => array(
									'type' => 'multi-measurement',
									'label' => __( 'Padding', 'siteorigin-premium' ),
									'default' => '30px 30px 30px 30px',
									'measurements' => array(
										'top' => array(
											'label' => __( 'Top', 'siteorigin-premium' ),
										),
										'right' => array(
											'label' => __( 'Right', 'siteorigin-premium' ),
										),
										'bottom' => array(
											'label' => __( 'Bottom', 'siteorigin-premium' ),
										),
										'left' => array(
											'label' => __( 'Left', 'siteorigin-premium' ),
										),
									),
								),
							),
						),
						'text' => array(
							'type' => 'section',
							'label' => __( 'Text', 'siteorigin-premium' ),
							'fields' => array(
								'font' => array(
									'type' => 'font',
									'label' => __( 'Font', 'siteorigin-premium' ),
								),
								'color' => array(
									'type' => 'color',
									'label' => __( 'Color', 'siteorigin-premium' ),
									'default' => '#ffffff',
								),
								'size' => array(
									'type' => 'measurement',
									'label' => __( 'Size', 'siteorigin-premium' ),
								),
								'link' => array(
									'type' => 'color',
									'label' => __( 'Link Color', 'siteorigin-premium' ),
									'default' => '#239cff',
								),
								'link_hover' => array(
									'type' => 'color',
									'label' => __( 'Link Hover Color', 'siteorigin-premium' ),
									'default' => 'rgba(35, 156, 255, 0.8)',
									'alpha' => true,
								),
								'text_margin' => array(
									'type' => 'measurement',
									'label' => __( 'Paragraph Margin Bottom', 'siteorigin-premium' ),
									'default' => '15px',
								),
							),
						),
					),
				),
			)
		);
	}

	public function filter_button_widget( $form_fields ) {
		$form_fields['text']['default'] = __( 'I Agree', 'siteorigin-premium' );
		unset( $form_fields['url'] );
		unset( $form_fields['new_window'] );
		unset( $form_fields['download'] );

		// Change defaults.
		$form_fields['design']['fields']['button_color']['default'] = '#239cff';
		$form_fields['design']['fields']['hover_background_color']['alpha'] = true;
		$form_fields['design']['fields']['hover_background_color']['default'] = 'rgba(35, 156, 255, 0.8)';

		return $form_fields;
	}

	public function register_assets() {
		wp_register_script(
			'siteorigin-premium-embed-blocker',
			plugin_dir_url( __FILE__ ) . 'js/script' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	public function additional_blocks( $content, $url, $settings ) {
		if (
			$url === 'connect.facebook.net' &&
			strpos( $content, 'fb-' ) !== false
		) {
			// The Facebook JavaScript SDK embeds have a 'fb' class prefix .
			$this->block_embed_element( $url, 'div', 'fb' );
		}

		return $this->content;
	}

	private function generate_less( $design = array() ) {
		if ( empty( $design ) ) {
			return;
		}

		$less = file_get_contents( plugin_dir_path( __FILE__ ) . 'less/style.less' );

		$container = $design['container'];
		$text = $design['text'];

		$vars = array(
			'container_background' => ! empty( $container['background'] ) ? $container['background'] : '',
			'container_border_color' => ! empty( $container['border_color'] ) ? '1px solid ' . $container['border_color'] : '',
			'container_border_radius' => ! empty( $container['border_radius'] ) ? $container['border_radius'] : '',
			'container_padding' => ! empty( $container['padding'] ) ? $container['padding'] : '',
			'text_color' => ! empty( $text['color'] ) ? $text['color'] : '',
			'text_size' => ! empty( $text['size'] ) ? $text['size'] : '',
			'text_link' => ! empty( $text['link'] ) ? $text['link'] : '',
			'text_link_hover' => ! empty( $text['link_hover'] ) ? $text['link_hover'] : '',
			'text_margin' => ! empty( $text['text_margin'] ) ? $text['text_margin'] . ' 0' : '',
		);

		if ( ! empty( $container['background_image'] ) ) {
			$background = siteorigin_widgets_get_attachment_image_src(
				$container['background_image'],
				'full'
			);

			if ( ! empty( $background ) ) {
				$vars['container_background_image'] = 'url(' . $background[0] . ')';
			}
		}

		if ( ! empty( $text['font'] ) ) {
			$font = siteorigin_widget_get_font( $text['font'] );
			$vars['text_font'] = $font['family'];

			if ( ! empty( $font['weight'] ) ) {
				$vars['text_style'] = $font['style'];
				$vars['text_weight'] = $font['weight_raw'];
			}
		}

		if ( ! empty( $vars ) ) {
			foreach ( $vars as $name => $value ) {
				// Ignore empty string, false and null values (but keep '0')
				if ( $value === '' || $value === false || $value === null ) {
					continue;
				}

				$less = preg_replace( '/\@' . preg_quote( $name ) . ' *\:.*?;/', '@' . $name . ': ' . $value . ';', $less );
			}
		}

		if ( ! class_exists( 'SiteOrigin_LessC' ) ) {
			require plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . 'base/inc/lessc.inc.php';
		}

		if ( ! class_exists( 'SiteOrigin_Widgets_Less_Functions' ) ) {
			require plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . 'base/inc/less-functions.php';
		}

		return $this->compile_css( $less );
	}

	private function compile_css( $less ) {
		if ( ! class_exists( 'SiteOrigin_LessC' ) ) {
			require plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . 'base/inc/lessc.inc.php';
		}

		$compiler = new SiteOrigin_LessC();

		try {
			if ( method_exists( $compiler, 'compile' ) ) {
				$css = @ $compiler->compile( $less );
			}
		} catch ( Exception $e ) {
			if ( defined( 'SITEORIGIN_WIDGETS_DEBUG' ) && SITEORIGIN_WIDGETS_DEBUG ) {
				throw $e;
			}
		}

		// Remove any attributes with default as the value
		$css = preg_replace( '/[a-zA-Z\-]+ *: *default *;/', '', $css );

		// Remove any empty CSS
		$css = preg_replace( '/[^{}]*\{\s*\}/m', '', $css );
		$css = trim( $css );

		return $css;
	}

	public function add_blocker_less() {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/embed-blocker' );

		if ( empty( $settings ) || empty( $settings['blocker_design'] ) ) {
			return;
		}

		$css = $this->generate_less( $settings['blocker_design'] );

		if ( ! empty( $css ) ) {
			echo '<style type="text/css">' . wp_strip_all_tags( $css ) . '</style>';
		}
	}

	public function process_content( $content ) {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/embed-blocker' );

		// If there aren't any embeds to block, return the content.
		if ( empty( $settings['content'] ) ) {
			return $content;
		}

		$this->content = $content;

		foreach ( $settings['content'] as $site ) {
			if ( empty( $site['status'] ) ) {
				continue;
			}

			$urls = explode( ',', $site['urls'] );

			foreach ( $urls as $url ) {
				$url = trim( $url );

				if ( empty( $url ) ) {
					continue;
				}

				if ( strpos( $this->content, $url ) !== false ) {
					// Found an embed. Let's block it.
					$this->setup_block_message( $settings, $site );
					$this->block_embed( $url, $site );
					$this->content = apply_filters(
						'siteorigin_premium_content_blocker_content',
						$this->content,
						$url,
						$settings
					);
					$load_assets = true;
				}
			}
		}

		if ( ! empty( $load_assets ) ) {
			wp_enqueue_script( 'siteorigin-premium-embed-blocker' );
		}

		return $this->content;
	}

	private function apply_addon_shortcodes( $settings, $site ) {
		$this->block_message = str_replace(
			'[site]',
			wp_kses_post( $site['label'] ),
			$this->block_message
		);

		$this->block_message = str_replace(
			'[privacy_link]',
			esc_url( $site['privacy_link'] ),
			$this->block_message
		);

		add_filter( 'siteorigin_widgets_button_attributes', array( $this, 'add_button_classes' ) );
		global $wp_widget_factory;
		$the_widget = $wp_widget_factory->widgets['SiteOrigin_Widget_Button_Widget'];
		ob_start();
		$the_widget->widget( array(), $settings['blocker_design']['button'] );
		$button = ob_get_clean();

		$this->block_message = str_replace(
			'[button]',
			$button,
			$this->block_message
		);
		remove_filter( 'siteorigin_widgets_button_attributes', array( $this, 'add_button_classes' ) );
	}

	public function add_button_classes( $attributes ) {
		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = '';
		}
		$attributes['class'] .= ' siteorigin-premium-embed-blocker-button';

		return $attributes;
	}

	private function setup_block_message( $settings, $site ) {
		$block_message = empty( $settings['message']['message_type'] ) || $settings['message']['message_type'] === 'tinymce' ?
			$settings['message']['tinymce'] :
			$settings['message']['builder'];

		if (
			isset( $settings['message']['message_type'] ) &&
			$settings['message']['message_type'] == 'builder' &&
			defined( 'SITEORIGIN_PANELS_VERSION' )
		) {
			$block_message = siteorigin_panels_render(
				'wSoPremiumContentBlocker',
				true, // $enqueue_css
				$settings['message']['builder']
			);
		} else {
			$block_message = do_shortcode( $settings['message']['tinymce'] );
		}

		if ( empty( $block_message ) ) {
			$block_message = $this->default_block_message();
		}

		$this->block_message = wp_kses_post( $block_message );

		$this->apply_addon_shortcodes( $settings, $site );

		$this->block_message = apply_filters(
			'siteorigin_premium_content_blocker_message',
			'<div class="siteorigin-premium-embed-blocker-message"><div class="siteorigin-premium-embed-blocker-message-content">' . $this->block_message . '</div></div>',
			$site['urls']
		);
	}

	/**
	 * Retrieves the platform name from a given URL.
	 *
	 * @param string $url The URL to extract the platform name from.
	 *
	 * @return string The platform name extracted from the URL.
	 */
	public function get_platform_name( $url ) {
		$url = explode( ',', $url )[0];
		$parts = explode( '.', $url );
		$count = count( $parts );

		// If $url has a www, return the second part.
		if ( $count > 2 && $parts[0] === 'www' ) {
			return $parts[1];
		} elseif ( $count === 2 ) {
			// If $url has exactly two parts, it's x.com.
			return $parts[0];
		} elseif ( count( $parts ) > 1 ) {
			// If $url has more than one part, it's subdomain.x.com.
			return $parts[1];
		}

		// This isn't actually a URl. Return the original in the hopes that works.
		return $url;
	}

	private function block_embed( $url, $site ) {
		$type = empty( $site['type'] ) ? 'iframe' : $site['type'];
		$this->currentSite = $site;

		if ( $type == 'blockquote' ) {
			// Blockquote embeds are a blockquote followed by a script element.
			$this->block_embed_element( $url, 'blockquote' );

			return $this->block_embed_script( $url, false );
		}

		if ( $type === 'script' ) {
			return $this->block_embed_script( $url );
		}

		return $this->block_embed_iframe( $url );
	}

	private function block_embed_element( $url, $element, $class = false ) {
		if ( ! $class ) {
			$class = $this->get_platform_name( $url );
		}

		$this->edit_content(
			'/<' . $element . ' class="' . $class . '-.*?<\/' . $element . '>/m',
			$element
		);
	}

	private function block_embed_script( $url, $add_block_message = true ) {
		$this->edit_content(
			'/<script[^>]*src=".*?' . $url . '[^>]*><\/script>/s',
			'script',
			$add_block_message
		);
	}

	private function block_embed_iframe( $url ) {
		$url = preg_quote( $url, '/' );

		$this->edit_content(
			"/<iframe[^>]*src=\"[^\"']*{$url}[^\"']*\"[^>]*><\/iframe>/m",
			'iframe'
		);
	}

	/**
	 * Replaces the 'href="#"' in the block message with 'href="#{block_id}"'.
	 *
	 * @param string $block_id The ID of the block.
	 *
	 * @return string The modified block message.
	 */
	private function edit_content_block_message( $block_id ) {
		return str_replace( 'href="#"', 'href="#' . $block_id . '"', $this->block_message );
	}

	/**
	 * Edits the content by replacing specified patterns with modified HTML code.
	 *
	 * @param string   $pattern           The regular expression pattern to match.
	 * @param string   $type              The HTML tag type to replace.
	 * @param bool     $add_block_message Whether to add a block message after the replacement.
	 *
	 * @return void
	 */
	private function edit_content( $pattern, $type, $add_block_message = true ) {
		$slug = $this->get_platform_name( $this->currentSite['urls'] );

		$this->content = preg_replace_callback(
			$pattern,
			function( $matches ) use ( $type, $add_block_message, $slug ) {
				$this->blockId++;
				$block_id = 'siteorigin-premium-embed-blocker-' . $this->blockId;
				$block_message = $add_block_message ? $this->edit_content_block_message( $block_id ) : '';

				return str_replace(
					array(
						'<' . esc_attr( $type ),
						'</' . esc_attr( $type ) . '>',
					),
					array(
						'<section
							id="' . $block_id . '"
							so-embed-blocker="true"
							style="display: none;"
							data-site-slug="' . esc_attr( $slug ) . '"
							data-type="' . esc_attr( $type ) . '"',
						'</section>',
					),
					$matches[0]
				) . $block_message;
			},
			$this->content
		);
	}
}
