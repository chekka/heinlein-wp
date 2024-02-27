<?php
/*
Plugin Name: SiteOrigin Video Background
Description: Introduce dynamic video backgrounds to any Page Builder row, column, or widget, adding an energetic touch to your site. Includes option for a semi-transparent overlay or pattern.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/video-background/
Minimum Version: siteorigin-panels 2.25.3
Tags: Page Builder
*/

class SiteOrigin_Premium_Plugin_Video_Background {
	public function __construct() {
		add_action( 'init', array( $this, 'init_addon' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function init_addon() {
		if (
			class_exists( 'SiteOrigin_Panels' ) &&
			(
				version_compare( SITEORIGIN_PANELS_VERSION, '2.25.3', '>=' ) ||
				SITEORIGIN_PANELS_VERSION == 'dev'
			)
		) {
			$this->add_filters();
		}
	}

	public function add_filters() {
		// Frontend.
		add_filter( 'siteorigin_panels_overlay', array( $this, 'has_video_background' ), 10, 2 );
		add_filter( 'siteorigin_panels_overlay_content', array( $this, 'add_video_background' ), 10, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'siteorigin_panels_row_classes', array( $this, 'add_context_class' ), 10, 2 );
		add_filter( 'siteorigin_panels_cell_classes', array( $this, 'add_context_class' ), 10, 2 );
		add_filter( 'siteorigin_panels_widget_classes', array( $this, 'add_widget_context_class' ), 10, 4 );

		// Backend.
		add_filter( 'siteorigin_panels_style_field_video_background', array( $this, 'style_field' ), 1, 5 );
		add_filter( 'siteorigin_panels_style_field_sanitize_video_background', array( $this, 'sanitize_field' ), 10, 4 );
		add_filter( 'siteorigin_panels_style_field_sanitize_all_video_background', array( $this, 'sanitize_field_fallback' ), 10, 5 );
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_filter( 'siteorigin_panels_general_style_fields', array( $this, 'add_style_field' ), 10, 3 );
	}

	public function style_field( $field, $field_name, $current, $field_id, $styles ) {
		ob_start();
		?>
		<div class="so-video-selector" tabindex="0">
			<span class="so-video-placeholder">
				<img
					src="<?php echo esc_url( includes_url( '/images/media/video.png' ) ); ?>"
					<?php
					if ( empty( $current ) ) {
						echo 'class="hidden"';
					}
					?>
				/>
			</span>

			<div class="select-video">
				<?php _e( 'Select Video', 'siteorigin-premium' ); ?>
			</div>
			<input
				type="hidden"
				name="<?php echo esc_attr( $field_name ); ?>"
				value="<?php echo (int) $current; ?>"
			/>
		</div>
		<a href="#" class="remove-video <?php if ( empty( (int) $current ) ) {
			echo ' hidden';
		} ?>"><?php _e( 'Remove', 'siteorigin-premium' ); ?></a>

		<input
			type="text"
			value="<?php echo esc_url( ! empty( $styles[ $field_id . '_fallback' ] ) ? $styles[ $field_id . '_fallback' ] : '' ); ?>"
			placeholder="<?php esc_attr_e( 'External URL', 'siteorigin-premium' ); ?>"
			name="<?php echo esc_attr( 'style[' . $field_id . '_fallback]' ); ?>"
			class="video-fallback widefat"
		/>
		<?php
		$field = ob_get_clean();
		return $field;
	}

	public function sanitize_field( $value, $field_id, $field, $styles ) {
		return (int) $value;
	}

	public function sanitize_field_fallback( $values, $value, $field_id, $field, $styles ) {
		$values[ $field_id . '_fallback' ] = ( ! empty( $styles[ $field_id . '_fallback' ] ) ? esc_url_raw( $styles[ $field_id . '_fallback' ] ) : '' );

		return $values;
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_script(
			'so-premium-video-background-field',
			plugin_dir_url( __FILE__ ) . 'js/video-background-field.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_enqueue_style(
			'so-premium-video-background-field',
			plugin_dir_url( __FILE__ ) . 'css/video-background-field.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_localize_script(
			'so-premium-video-background-field',
			'soVideoBackgroundField',
			array(
				'add_media' => __( 'Choose Video', 'siteorigin-premium' ),
				'add_media_done' => __( 'Done', 'siteorigin-premium' ),
			)
		);
	}

	public function add_style_field( $fields, $post_id, $args ) {
		$fields['video_background'] = array(
			'name' => __( 'Background Video', 'siteorigin-premium' ),
			'type' => 'video_background',
			'group' => 'design',
			'priority' => 9.1, // Not ideal, but we can't use 10 due to the Border Color field.
		);

		$fields['video_background_opacity'] = array(
			'name'        => __( 'Background Video Opacity', 'siteorigin-premium' ),
			'type'        => 'slider',
			'group'       => 'design',
			'priority'    => 9.2,
		);

		$fields['video_background_play_once'] = array(
			'label'        => __( 'Loop Video', 'siteorigin-premium' ),
			'type'        => 'checkbox',
			'group'       => 'design',
			'priority'    => 9.3,
			'default' => true,
		);

		$fields['video_background_display'] = array(
			'name'        => __( 'Background Video Display', 'siteorigin-premium' ),
			'type'        => 'select',
			'group'       => 'design',
			'priority'    => 9.4,
			'options'     => array(
				'full' => __( 'Full', 'siteorigin-premium' ),
				'cover' => __( 'Cover', 'siteorigin-premium' ),
			),
		);

		return $fields;
	}

	public function enqueue_frontend_scripts() {
		if ( ! wp_script_is( 'fitvids' ) ) {
			wp_register_script(
				'fitvids',
				siteorigin_panels_url( 'js/lib/jquery.fitvids' . SITEORIGIN_PANELS_JS_SUFFIX . '.js' ),
				array( 'jquery' ),
				SITEORIGIN_PANELS_VERSION
			);
		}
		wp_register_script(
			'so-premium-video-background',
			plugin_dir_url( __FILE__ ) . 'js/video-background' . SITEORIGIN_PANELS_JS_SUFFIX . '.js',
			array( 'jquery', 'fitvids' ),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_enqueue_style(
			'so-premium-video-background',
			plugin_dir_url( __FILE__ ) . 'css/style.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);
	}

	public function has_video_background( $status, $context ) {
		if (
			! empty( $context['style']['video_background'] ) ||
			! empty( $context['style']['video_background_fallback'] )
		) {
			$status = true;
		}

		return $status;
	}

	private static function get_format( $url ) {
		$extension = pathinfo( $url, PATHINFO_EXTENSION );
		switch ( $extension ) {
			case 'm4v':
				return 'video/mp4';
			case 'mov':
				return 'video/quicktime';
			case 'wmv':
				return 'video/x-ms-wmv';
			case 'avi':
				return 'video/x-msvideo';
			case 'mpg':
				return 'video/mpeg';
			case 'ogv':
			case 'ogg':
				return 'video/ogg';
			case 'webm':
				return 'video/webm';
			case 'mp4':
			default:
				return 'video/mp4';
		}
	}

	public function add_video_background( $html, $context, $custom_overlay ) {
		if ( $custom_overlay ) {
			$style = $context['style'];
			if ( ! empty( $style['video_background'] ) ) {
				$video = $style['video_background'];
			} elseif ( ! empty( $style['video_background_fallback'] ) ) {
				$video = $style['video_background_fallback'];
			}

			if ( ! empty( $video ) ) {
				wp_enqueue_script( 'so-premium-video-background' );

				if ( empty( $style['video_background_opacity'] ) ) {
					return $html;
				}

				if ( ! class_exists( 'SiteOrigin_Video' ) ) {
					require_once plugin_dir_path( __FILE__ ) . 'inc/video.php';
				}
				$so_video = new SiteOrigin_Video();

				$opacity = null;
				if (
					! empty( $style['video_background_opacity'] ) &&
					$style['video_background_opacity'] !== 100
				 ) {
					$opacity = 'style="opacity: ' . ( (int) $style['video_background_opacity'] / 100 ) . ';"';
				}

				echo '<div class="so-premium-video-background" ' . $opacity . '>';

				// If $video isn't numeric it's a URL.
				if ( ! is_numeric( $video ) ) {
					$can_oembed = $so_video->can_oembed( $video );

					// Check if we can oEmbed the video or not.
					if ( ! $can_oembed ) {
						$video_file = sow_esc_url( $video );
					} else {
						echo $so_video->get_video_oembed( $video, true, false, true, true );
					}
				}

				if ( is_numeric( $video ) || isset( $video_file ) ) {
					$loop = ( ! isset( $style['video_background_play_once'] ) || $style['video_background_play_once'] ) ? ' loop' : '';

					$border_radius = '';
					if ( ! empty( $style['border_radius'] ) ) {
						$border_radius = 'style="border-radius:' . esc_attr( $style['border_radius'] ). ';"';
					}

					$video_element = "<video autoplay muted playsinline $loop $border_radius>";
					// If $video_file isn't set video is a local file.
					if ( ! isset( $video_file ) ) {
						$video_file = wp_get_attachment_url( $video );
					}

					$video_element .= '<source
						src="' . sow_esc_url( $video_file ) . '"
						type="' . self::get_format( $video_file ) . '"
					>';

					if ( strpos( $video_element, 'source' ) !== false ) {
						$video_element .= '</video>';
						echo $video_element;
					}
				}
				echo '</div>';
			}
		}

		return $html;
	}

	public function add_context_class( $classes, $context ) {
		if (
			(
				! empty( $context['style']['video_background'] ) ||
				! empty( $context['style']['video_background_fallback'] )
			) &&
			! empty( $context['style']['video_background_display'] ) &&
			$context['style']['video_background_display'] === 'cover'
		) {
			$classes[] = 'panel-video-background-cover';
		}

		return $classes;
	}

	public function add_widget_context_class( $classes, $widget_class, $instance, $widget_info ) {
		return $this->add_context_class( $classes, $instance['panels_info'] );
	}
}
