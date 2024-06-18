<?php
if ( empty( $this->settings ) ) {
	die();
}
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
	<head>
		<title>
			<?php echo esc_html( apply_filters( 'siteorigin_premium_gate_title', $this->gate_setting( 'title' ) ) ); ?>
		</title>
		<link rel='stylesheet' id='admin-bar-css' href='<?php echo SiteOrigin_Panels::front_css_url(); ?>' type='text/css' media='all' />
		<?php do_action( 'siteorigin_premium_gate_head' ); ?>
		<?php
		if ( $force_gate ) {
			wp_head();
		}
		?>
		<style>
			body {
				<?php if ( $this->gate_setting( 'background_color', 'body' ) ) { ?>
					background-color: <?php echo esc_html( $this->gate_setting( 'background_color', 'body' ) ); ?>;
				<?php } ?>

				<?php if ( $this->gate_setting( 'color', 'text' ) ) { ?>
					color: <?php echo esc_html( $this->gate_setting( 'color', 'text' ) ); ?>;
				<?php } ?>

				align-items: flex-start;
				display: flex;
				height: 100%;
				line-height: 1.6;
				margin: 0;

				<?php
				$alignment = $this->gate_setting( 'alignment', 'container' );
				if ( $alignment == 'center' ) {
					?>
					justify-content: center;
				<?php } elseif ( $alignment == 'right' ) { ?>
					justify-content: flex-end;
				<?php } ?>

				<?php
				if ( $this->gate_setting( 'font', 'text' ) ) {
					$font = siteorigin_widget_get_font( $this->gate_setting( 'font', 'text' ) );
					?>
					font-family: <?php echo esc_html( $font['family'] ); ?>;

					<?php
					if ( ! empty( $font['weight'] ) ) {
						if ( ! empty( $font['style'] ) ) {
							?>
							font-style: <?php echo esc_html( $font['style'] ); ?>;
							<?php
						}

						if ( ! empty( $font['weight_raw'] ) ) {
							?>
							font-weight: <?php echo esc_html( $font['weight_raw'] ); ?>;
							<?php
						}
					}
				}
				?>

				<?php if ( $this->gate_setting( 'font_size', 'text' ) ) { ?>
					font-size: <?php echo esc_html( $this->gate_setting( 'font_size', 'text' ) ); ?>;
				<?php } ?>
			}


			<?php
			if ( $this->gate_setting( 'background_image', 'body' ) ) {
				$src = siteorigin_widgets_get_attachment_image_src(
					$this->gate_setting( 'background_image', 'body' ),
					'full'
				);

				if ( ! empty( $src ) ) {
					$add_background = true;
					?>
					.background {
						background-image: url( <?php echo esc_url( $src[0] ); ?> );
						background-repeat: no-repeat;
						background-size: cover;
						bottom: 0;
						left: 0;
						position: absolute;
						right: 0;
						top: 0;
						<?php if ( $this->gate_setting( 'background_image_opacity', 'body' ) ) { ?>
							opacity: 0.<?php echo esc_html( $this->gate_setting( 'background_image_opacity', 'body' ) ); ?>;
						<?php } ?>
					}
					<?php
				}
			}
			?>

			<?php if ( $this->gate_setting( 'link', 'text' ) ) { ?>
				a {
					color: <?php echo esc_html( $this->gate_setting( 'link', 'text' ) ); ?>;
				}
			<?php } ?>

			<?php if ( $this->gate_setting( 'link_hover', 'text' ) ) { ?>
				a:hover,
				a:focus {
					color: <?php echo esc_html( $this->gate_setting( 'link_hover', 'text' ) ); ?>;
				}
			<?php } ?>

			.entry-content {
				<?php
				if ( $this->gate_setting( 'background_color', 'container' ) ) {
					?>
					background-color: <?php echo esc_html( $this->gate_setting( 'background_color', 'container' ) ); ?>;
				<?php } ?>

				<?php
				if ( $this->gate_setting( 'border_radius', 'container' ) ) {
					?>
					border-radius: <?php echo esc_html( $this->gate_setting( 'border_radius', 'container' ) ); ?>;
				<?php } ?>

				<?php $max_width = $this->gate_setting( 'width', 'container' ); ?>
				max-width: <?php echo esc_html( apply_filters(
					'siteorigin_gate_max_width',
					! empty( $max_width ) ? $max_width : '820px'
				) ); ?>;

				<?php if ( $this->gate_setting( 'margin', 'container' ) ) { ?>
					margin: <?php echo esc_html( $this->gate_setting( 'margin', 'container' ) ); ?>;
				<?php } ?>

				<?php if ( $this->gate_setting( 'padding', 'container' ) ) { ?>
					padding: <?php echo esc_html( $this->gate_setting( 'padding', 'container' ) ); ?>;
				<?php } ?>
				z-index: 1;
			}

			h1 {
				margin-block-start: 0;
			}

			h1, h2, h3, h4, h5, h6 {
				<?php if ( $this->gate_setting( 'color', 'heading' ) ) { ?>
					color: <?php echo esc_html( $this->gate_setting( 'color', 'heading' ) ); ?>;
				<?php } ?>

				<?php if ( $this->gate_setting( 'font', 'heading' ) ) {
					$font = siteorigin_widget_get_font( $this->gate_setting( 'font', 'heading' ) );
					?>
					font-family: <?php echo esc_html( $font['family'] ); ?>;

					<?php
					if ( ! empty( $font['weight'] ) ) {
						if ( ! empty( $font['style'] ) ) {
							?>
							font-style: <?php echo esc_html( $font['style'] ); ?>;
							<?php
						}

						if ( ! empty( $font['weight_raw'] ) ) {
							?>
							font-weight: <?php echo esc_html( $font['weight_raw'] ); ?>;
							<?php
						}
					}
				}
				?>

				<?php if ( $this->gate_setting( 'font_size', 'heading' ) ) { ?>
					font-size: <?php echo esc_html( $this->gate_setting( 'font_size', 'heading' ) ); ?>;
				<?php } ?>
			}
			<?php do_action( 'siteorigin_premium_gate_css' ); ?>
		</style>
	</head>
	<body>
		<div class="entry-content">
			<?php
			do_action( 'siteorigin_premium_gate_content' )
			?>
		</div>
		<?php
		SiteOrigin_Panels_Styles::register_scripts();
		wp_print_scripts();
		wp_print_styles();
		if ( $force_gate ) {
			wp_footer();
		}
		do_action( 'siteorigin_premium_gate_footer' );

		if ( isset( $add_background ) ) {
			?>
			<div class="background">&nbsp;</div>
			<?php
		}
		?>
	</body>
</html>
<?php
die();
