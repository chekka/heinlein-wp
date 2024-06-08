<?php
/**
 * @var bool $license_active
 */
?>
<div class="wrap siteorigin-premium-wrap" id="siteorigin-premium-license">

	<div class="page-header">
		<div class="so-premium-icon-wrapper">
			<img src="<?php echo SiteOrigin_Premium::dir_url( __FILE__ ); ?>../img/page-icon.png" class="so-premium-icon" />
		</div>
		<h1><?php esc_html_e( 'SiteOrigin Premium License', 'siteorigin-premium' ); ?></h1>
	</div>

	<?php $this->display_key_message( $key ); ?>

	<div class="page-main">
		<form action="<?php echo esc_url( add_query_arg( 'action', 'save' ) ); ?>" method="post">

			<label for="siteorigin-premium-key" class="license-key-label">
				<?php if ( $license_active ) { ?>
					<span class="dashicons dashicons-yes"></span>
				<?php } ?>

				<?php esc_html_e( 'License Key', 'siteorigin-premium' ); ?>

				<?php if ( $license_active ) { ?>
					<span class="license-status">
						<?php esc_html_e( 'Your license key is valid and active.', 'siteorigin-premium' ); ?>
					</span>
				<?php } ?>
			</label>
			<div class="key-entry-field">
				<div class="field-wrapper">
					<input type="submit" class="button-secondary" value="<?php esc_attr_e( 'Save', 'siteorigin-premium' ); ?>">

					<div class="input-wrapper">
						<input type="password" name="siteorigin_premium[key]" id="siteorigin-premium-key" value="<?php echo esc_attr( $key ); ?>" />
					</div>
				</div>

				<?php if ( ! empty( $partial_key ) ) { ?>
					<p class="key-indicator">
						<?php printf( esc_html( 'Your license key ends in %s', 'siteorigin-premium' ), $partial_key ); ?>
					</p>
				<?php } ?>
			</div>

			<?php wp_nonce_field( 'save_siteorigin_premium' ); ?>
		</form>
	</div>

	<div class="siteorigin-logo">
		<p>
			<?php esc_html_e( 'Proudly Created By', 'siteorigin' ); ?>
		</p>
		<a href="https://siteorigin.com/" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo SiteOrigin_Premium::dir_url( __FILE__ ); ?>../img/siteorigin.png" />
		</a>
	</div>

</div>
