<?php
while ( $settings['posts']->have_posts() ) {
	$settings['posts']->the_post();
	$img = siteorigin_widgets_get_attachment_image_src(
		get_post_thumbnail_id(),
		$settings['image_size'],
		$settings['default_thumbnail']
	);
	?>
	<div
		class="sow-carousel-item <?php if ( has_post_thumbnail() || ! empty( $settings['default_thumbnail'] ) ) {
			echo 'sow-carousel-default-thumbnail';
		} ?>"
		style="
			<?php echo ! empty( $img ) ? 'background-image: url(' . sow_esc_url( $img[0] ) . ');' : ''; ?>
			<?php echo ! empty( $height ) ? "$height;" : ''; ?>
			opacity: 0;
		"
	>
		<a
			href="<?php the_permalink(); ?>"
			<?php echo $settings['link_target'] == 'new' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
			tabindex="-1"
			aria-labelledby="sow-carousel-id-<?php echo the_ID(); ?>"
			class="sow-carousel-overlay"
		>
			&nbsp;
		</a>

		<?php if ( has_category() ) { ?>
			<span class="sow-entry-categories">
				<?php echo the_category( esc_html__( ' ', 'siteorigin-premium' ) ); ?>
			</span>
		<?php } ?>

		<<?php echo esc_attr( $settings['item_title_tag'] ); ?>
			class="sow-carousel-item-title" 
			id="sow-carousel-id-<?php echo the_ID(); ?>"
		>
			<?php echo esc_html( get_the_title() ); ?>
		</<?php echo esc_attr( $settings['item_title_tag'] ); ?>>
	</div>
<?php
}
wp_reset_postdata();
