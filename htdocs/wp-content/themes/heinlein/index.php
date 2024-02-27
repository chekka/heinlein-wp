<?php get_header(); ?>

		<main id="main" class="site-main">
			<?php the_content(); ?>
   <div class="content-footer">
    <div class="top">
					<?php dynamic_sidebar('footer-top'); ?>
				</div>
				<div class="bottom">
					<?php dynamic_sidebar('footer-bottom'); ?>
				</div>
   </div>
		</main>

<?php get_footer();		