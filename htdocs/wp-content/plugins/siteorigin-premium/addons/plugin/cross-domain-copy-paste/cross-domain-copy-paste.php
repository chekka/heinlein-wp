<?php
/*
Plugin Name: SiteOrigin Cross Domain Copy Paste
Description: Streamline your site development by effortlessly copying and pasting rows, columns, and widgets across domains, saving time and enhancing creative continuity.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/cross-domain-copy-paste
Tags: Page Builder
Requires: siteorigin-panels
*/

class SiteOrigin_Premium_Plugin_cross_domain_copy_paste {
	var $assetsOutput;

	public function __construct() {
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'siteorigin_premium_addons_page_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ), 20 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	// Add global addon settings.
	public function get_settings_form() {
		return new SiteOrigin_Premium_Form(
			'so-addon-cross-domain-copy-paste-settings',
			array(
				'method' => array(
					'type' => 'radio',
					'label' => __( 'Copy and Paste Method', 'siteorigin-premium' ),
					'options' => array(
						'clipboard' => __( 'Browser Clipboard', 'siteorigin-premium' ),
						'storage' => __( 'Browser Storage', 'siteorigin-premium' ),
					),
					'default' => 'clipboard',
					'state_emitter' => array(
						'callback' => 'select',
						'args' => array( 'method' ),
					),
				),
				'browser_storage' => array(
					'type' => 'html',
					'markup' => '<button class="button-secondary so-premium-copy-paste-prompt">' .
						__( 'Grant Permission', 'siteorigin-premium' ) .
						'</button>',
					'state_handler' => array(
						'method[storage]' => array( 'show' ),
						'_else[method]' => array( 'hide' ),
					),
				),
				'intro' => array(
					'type' => 'html',
					'markup' => '<p><strong>' . __( 'Browser Clipboard Method', 'siteorigin-premium' ) . '</strong><br>
					' . __( 'Right-click on a Page Builder source row or widget, click Copy Row or Copy Widget.', 'siteorigin-premium' ) . '<br>'
					. __( 'Go to your destination page, locate the Cross Domain Copy Paste field below Page Builder, right-click, and Paste your data.', 'siteorigin-premium' ) . '<br>'
					. __( 'Finally, right-click within Page Builder and select Paste Row or Paste Widget from the contextual menu.', 'siteorigin-premium' ) . '<br></p>
					<p><strong>' . __( 'Browser Storage Method', 'siteorigin-premium' ) . '</strong><br>'
					. __( 'Click the Grant Permission button conditionally displayed below the Browser Storage radio button.', 'siteorigin-premium' ) . '<br>'
					. __( 'Grant permissions on both the source and destination websites.', 'siteorigin-premium' ) . '<br>'
					. __( 'Right-click on a Page Builder source row or widget, click Copy Row or Copy Widget.', 'siteorigin-premium' ) . '<br>'
					. __( 'Go to your destination page, right-click in Page Builder, and click Paste Row or Paste Widget.', 'siteorigin-premium' ),
				),
			)
		);
	}

	public function enqueue_admin_assets( $hook_suffix = '' ) {
		if ( ! empty( $this->assetsOutput ) ) {
			return;
		}
		$this->assetsOutput = true;

		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/cross-domain-copy-paste' );

		// Load the default method if it's not set.
		if (
			empty( $settings['method'] ) ||
			(
				$settings['method'] != 'clipboard' &&
				$settings['method'] != 'storage'
			)
		) {
			$settings['method'] = 'clipboard';
		}

		if ( $settings['method'] == 'clipboard' && ! empty( $hook_suffix ) ) {
			if ( $hook_suffix === 'widgets.php' ) {
				add_action( 'admin_footer-widgets.php', array( $this, 'widgets_page_markup' ) );
			}
		}

		wp_enqueue_script(
			'siteorigin-premium-cross-domain-copy-paste-addon',
			plugin_dir_url( __FILE__ ) . 'js/addon' . SITEORIGIN_PREMIUM_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SITEORIGIN_PREMIUM_VERSION
		);

		wp_localize_script(
				'siteorigin-premium-cross-domain-copy-paste-addon',
				'soPremiumCrossDomainCopyPaste',
				array(
					'loc' => esc_html__( 'Cross Domain Paste', 'siteorigin-premium' ),
					'method' => esc_html( $settings['method'] ),
					'success' => esc_html__( 'Success. You can now right-click and paste in Page Builder.', 'siteorigin-premium' ),
					'fail' => esc_html__( 'Something went wrong. Please try copying the data again.', 'siteorigin-premium' ),
					'https' => esc_html__( 'Your website must use HTTPS for the SiteOrigin Premium Cross Domain Copy Paste Addon to function.', 'siteorigin-premium' ),
				)
			);

		wp_enqueue_style(
			'siteorigin-premium-cross-domain-copy-paste-addon',
			plugin_dir_url( __FILE__ ) . 'css/addon.css',
			array(),
			SITEORIGIN_PREMIUM_VERSION
		);

		global $pagenow;
		// Load Browser Storage Method if on the addons page, or the user has enabled the storage method.
		if (
			$settings['method'] == 'storage' ||
			(
				$pagenow == 'admin.php' &&
				! empty( $_GET['page'] ) &&
				$_GET['page'] == 'siteorigin-premium-addons'
			)
		) {
			?>
			<div class="siteorigin-premium-cross-domain-copy-paste-container" data-src="https://clipboard.siteorigin.com" style="display: none;">
				<div class="siteorigin-premium-cross-domain-copy-paste-overlay"></div>
			</div>
		<?php
		}
	}

	public function widgets_page_markup() {
		?>
		<div class="siteorigin-premium-copy-page-widgets" style="display: none;">
			<div class="siteorigin-premium-copy-page-widgets-fields">
				<div class="siteorigin-widget-field siteorigin-widget-field-type-textarea siteorigin-widget-field-copy_paste_data">
					<label for="widget-siteorigin-premium-1-copy_paste-copy_paste_data-1" class="siteorigin-widget-field-label"></label>
					<textarea type="text" name="widget-siteorigin-premium[1][copy_paste][copy_paste_data]" id="widget-siteorigin-premium-1-copy_paste-copy_paste_data-1" rows="4" class="widefat siteorigin-widget-input"></textarea>
				</div>

				<div class="siteorigin-widget-field siteorigin-widget-field-type-html siteorigin-widget-field-note">
					<div class="siteorigin-widget-html-field">
						Paste your row or widget data into the above field, then right-click and paste in Page Builder. <a href="https://siteorigin.com/premium-documentation/plugin-addons/cross-domain-copy-paste/" target="_blank" rel="noopener noreferrer">Getting Started</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function metabox_options( $form_options ) {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/cross-domain-copy-paste' );

		// If the the addon isn't in clipboard mode, don't add the metabox.
		if (
			! empty( $settings['method'] ) &&
			$settings['method'] != 'clipboard'
		) {
			return $form_options;
		}

		return $form_options + array(
			'copy_paste' => array(
				'type' => 'section',
				'label' => __( 'Cross Domain Copy Paste' , 'siteorigin-premium' ),
				'tab' => true,
				'hide' => true,
				'fields' => array(
					'copy_paste_data' => array(
						'type' => 'textarea'
					),
					'note' => array(
						'markup' => sprintf( __( 'Paste your row or widget data into the above field, then right-click and paste in Page Builder. %sGetting Started%s' , 'siteorigin-premium' ), '<a href="https://siteorigin.com/premium-documentation/plugin-addons/cross-domain-copy-paste/" target="_blank" rel="noopener noreferrer">', '</a>' ),
						'type' => 'html'
					),
				),
			),
		);
	}
}
