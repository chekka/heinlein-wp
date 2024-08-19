<?php
/**
 * @var array   $builder_supports
 * @var string  $preview_url
 * @var string  $delete_url
 * @var string  $current_tab
 * @var array   $current_template
 * @var array   $panels_data
 * @var int     $template_post_id
 * @var int     $default_template_post_id
 * @var WP_Post $template_post
 * @var bool    $template_enabled
 * @var bool    $allow_multiple_templates
 * @var array   $template_posts
 * @var array   $so_wc_templates
 */
?>
<h2>
	<?php esc_html_e( 'WooCommerce Template Builder', 'siteorigin-premium' ); ?>
</h2>
<ul class="so-wc-templates-nav">
	<?php foreach ( $so_wc_templates as $slug => $wc_template ) {
		$url = admin_url( 'admin.php?page=so-wc-templates&tab=' . $slug );
		$active = $current_tab == $slug;
		?>
		<li<?php echo $active ? ' class="active"' : ''; ?>>
			<a href="<?php echo esc_attr( $url ); ?>">
				<?php echo ucwords( esc_html( $wc_template['label'] ) ); ?>
				<?php if ( $active ) { ?>
					<span class="dashicons dashicons-info so-wc-templates-tooltip">
						<span class="so-wc-templates-tooltip-contents ">
							<?php echo esc_html( $wc_template['info'] ); ?>
						</span>
					</span>
				<?php } ?>
			</a>
		</li>
	<?php } ?>
</ul>

<div class="wrap" id="so-premium-wc-template">
	<form
		action="<?php echo esc_url( add_query_arg( array( 'page' => 'so-wc-templates', 'tab' => $current_tab ) ) ); ?>"
		class="hide-if-no-js siteorigin-panels-builder-form so-premium-wc-tab-<?php echo esc_attr( $current_tab ); ?>"
		method="post"
		id="so-premium-wc-template-form"
		data-type="so_premium_wc_template"
		data-post-id="<?php echo (int) $template_post_id; ?>"
		data-preview-url="<?php echo esc_url( $preview_url ); ?>"
		data-builder-supports="<?php echo esc_attr( wp_json_encode( $builder_supports ) ); ?>"
	>
		<?php if ( $allow_multiple_templates ) { ?>
			<?php if ( ! empty( $template_posts ) ) { ?>
				<div id="so-wc-selected-template-container" class="so-wc-template-input-container">
					<label for="so-wc-selected-template"><?php esc_html_e( 'Template', 'siteorigin-premium' ); ?></label>
					<select id="so-wc-selected-template" name="so-wc-selected-template">
						<option value=""><?php esc_html_e( 'Add New Template', 'siteorigin-premium' ); ?></option>
						<?php foreach ( $template_posts as $tmplt_post ) {
							$default_label = '';

							if ( ! empty( $default_template_post_id ) && $default_template_post_id == $tmplt_post->ID ) {
								$default_label = ' (' . __( 'default', 'siteorigin-premium' ) . ')';
							}
							?>
							<option
								value="<?php echo esc_attr( $tmplt_post->ID ); ?>"
								<?php selected( $tmplt_post->ID, $template_post_id ); ?>>
									<?php echo esc_html( $tmplt_post->post_title . $default_label ); ?>
							</option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
			<?php if ( $current_tab == 'content-product' ) { ?>
				<div id="so-wc-edit-name-container" class="so-wc-template-input-container">
					<input
						type="text"
						id="so-wc-template-name"
						name="so-wc-template-name"
						placeholder="<?php esc_attr_e( 'Template Title', 'siteorigin-premium' ); ?>"
						value="<?php echo ! empty( $template_post ) ? $template_post->post_title : ''; ?>"
						<?php if ( empty( $template_post ) ) {
							echo 'autofocus';
						} ?>/>
				</div>
				<h4><?php esc_html_e( 'Before Product Archive Loop', 'siteorigin-premium' ); ?></h4>
				<?php
				// In some contexts this is already encoded so we need to account for that.
				$template_before = get_option( "so-wc-templates-before-$template_post_id" );
				$valid_string = is_string( $template_before );

				if ( empty( $valid_string ) ) {
					$template_before = json_encode( $template_before );
				}
				?>
				<div
					class="wctb-builder"
					data-mode="dialog"
					data-type="wctb-archive-builder"
				>
					<p>
						<button class="button-secondary siteorigin-panels-display-builder" ><?php esc_html_e( 'Open Builder', 'siteorigin-premium' ); ?></button>
					</p>
					<input
						type="hidden"
						class="siteorigin-widget-input panels-data"
						value="<?php echo sow_esc_attr( $template_before, ENT_QUOTES, false, true ); ?>"
						name="content-product-before"
						id="contentproductbefore"
					/>
				</div>
				<h4><?php esc_html_e( 'Product Archive Loop', 'siteorigin-premium' ); ?></h4>
			<?php } else { ?>
				<div id="so-wc-edit-name-container" class="so-wc-template-input-container">
					<input
						type="text"
						id="so-wc-template-name"
						name="so-wc-template-name"
						placeholder="<?php esc_attr_e( 'Template Title', 'siteorigin-premium' ); ?>"
						value="<?php echo ! empty( $template_post ) ? esc_attr( $template_post->post_title ) : ''; ?>"
						<?php if ( empty( $template_post ) ) {
							echo 'autofocus';
						} ?>/>
				</div>
			<?php } ?>
		<?php } ?>

		<div class="siteorigin-panels-builder-container so-panels-loading">
		</div>

		<?php if ( $current_tab == 'content-product' ) { ?>
			<h4><?php esc_html_e( 'After Product Archive Loop', 'siteorigin-premium' ); ?></h4>
			<?php
			// In some contexts this is already encoded so we need to account for that.
			$template_after = get_option( "so-wc-templates-after-$template_post_id" );
			$valid_string = is_string( $template_after );

			if ( empty( $valid_string ) ) {
				$template_after = json_encode( $template_after );
			}
			?>

			<div
				class="wctb-builder"
				data-mode="dialog"
				data-type="wctb-archive-builder"
			>
				<p>
					<button class="button-secondary siteorigin-panels-display-builder" ><?php esc_html_e( 'Open Builder', 'siteorigin-premium' ); ?></button>
				</p>
				<input
					type="hidden"
					class="siteorigin-widget-input panels-data"
					value="<?php echo sow_esc_attr( $template_after, ENT_QUOTES, false, true ); ?>"
					name="content-product-after"
					id="contentproductafter"
				/>
			</div>
		<?php } ?>

		<script type="text/javascript">
			( function( builderId, panelsData ){
				// Create the panels_data input
				document.write( '<input name="panels_data" type="hidden" class="siteorigin-panels-data-field" id="panels-data-field-' + builderId + '" />' );
				document.getElementById( 'panels-data-field-' + builderId ).value = JSON.stringify( panelsData );
			} )( "so-wc-template", <?php echo wp_json_encode( $panels_data ); ?> );
		</script>
		<p>
			<label for="so-wc-activate" class="so-wc-activate-checkbox">
				<input type="checkbox" id="so-wc-activate" name="so-wc-activate" value="enabled" <?php checked( $template_enabled ); ?>/>
				<?php if ( ! empty( $allow_multiple_templates ) ) { ?>
					<?php esc_html_e( 'Set as Default', 'siteorigin-premium' ); ?>
				<?php } else { ?>
					<?php esc_html_e( 'Enable Template', 'siteorigin-premium' ); ?>
				<?php } ?>
			</label>
		</p>
		<div class="so-wc-actions">
			<div>

				<input type="submit" class="button button-primary" id="so-wc-save-template" value="<?php esc_attr_e( 'Save Template', 'siteorigin-premium' ); ?>" />
			<?php if ( ! empty( $preview_url ) ) { ?>
				<button
					id="so-wc-preview-template"
					type="button"
					data-preview-url="<?php echo esc_url( $preview_url ); ?>"
					class="button action-button"
					title="<?php esc_attr_e( 'Preview template', 'siteorigin-premium' ); ?>"><span class="dashicons dashicons-visibility"></span></button>
			<?php } ?>
			<?php if ( ! empty( $allow_multiple_templates ) && ! empty( $template_post_id ) ) { ?>
				<a
					id="so-wc-delete-template"
					href="<?php echo esc_url( $delete_url ); ?>"
					class="button action-button"
					title="<?php esc_attr_e( 'Delete Template', 'siteorigin-premium' ); ?>"><span class="dashicons dashicons-trash"></span></a>
			<?php } ?>
			</div>
			<?php if ( $current_tab === 'content-single-product' ) { ?>
				<label for="so-wc-shortcode"">
					<?php esc_html_e( 'Template Shortcode:', 'siteorigin-premium' ); ?>
					<input type="text" value='[sowctb template="<?php echo esc_attr( (int) $template_post_id ); ?>" product="ProductId"]' onclick="this.select();" readonly>
				</label>
			<?php } ?>
		</div>
		<input type="hidden" id="post_content" name="post_content"/>
		<?php wp_nonce_field( 'update', '_so_wc_template_nonce' ); ?>
		<input type="hidden" id="post_content" name="template_post_id" value="<?php echo (int) $template_post_id; ?>"/>
	</form>
	<noscript><p><?php esc_html_e( 'This interface requires Javascript', 'siteorigin-premium' ); ?></p></noscript>
	<script type="text/template" id="so-premium-wc-template-preview">
		<div id="so-premium-wc-template-preview-dialog">
			<div class="so-overlay"></div>

			<div class="so-title-bar">
				<h3 class="so-title"><?php esc_html_e( 'SiteOrigin WooCommerce Template Preview', 'siteorigin-premium' ); ?></h3>
				<a class="so-close">
					<span class="so-dialog-icon"></span>
				</a>
			</div>

			<div class="so-content so-loading">
			</div>

			<div class="so-toolbar">
				<div class="so-buttons">
					<button class="button-primary so-close"><?php esc_html_e( 'Close', 'siteorigin-premium' ); ?></button>
				</div>
			</div>
		</div>
	</script>
</div>
