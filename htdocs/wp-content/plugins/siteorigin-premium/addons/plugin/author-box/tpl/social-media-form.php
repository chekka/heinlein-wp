<?php
if ( empty( $networks ) ) {
	return;
}
?>
<h2><?php esc_html_e( 'SiteOrigin Author Bio Social Media', 'siteorigin-premium' ); ?></h2>
<table class="form-table" role="presentation">
	<tbody>
	<?php
	foreach ( $networks as $network => $url ) {
		$value = isset( $user_meta[ $network ] ) ? $user_meta[ $network ] : '';
		$label = empty( $this->widget_networks[ $network ] ) ? $network : $this->widget_networks[ $network ]['label'];
		?>
		<tr>
			<th>
				<label for="so_premium_author_bio_social_media_<?php echo esc_attr( $network ); ?>">
					<?php echo esc_html( ucfirst( $label ) ); ?>
				</label>
			</th>
			<td>
				<input
					type="text"
					name="so_premium_author_bio_social_media[<?php echo esc_attr( $network ); ?>]"
					id="so_premium_author_bio_social_media_<?php echo esc_attr( $network ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					class="regular-text"
				/>
				<?php if ( ! empty( $url ) ) { ?>
					<p class="description">
						<?php echo esc_html( sprintf( __( 'Your username will be appended to %s', 'siteorigin-premium' ), esc_url( $url ) ) ); ?>
					</p>
				<?php } ?>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
