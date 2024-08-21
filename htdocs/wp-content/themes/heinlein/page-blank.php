<?php /* Template Name: Blank page */ ?>

<?php get_header(); ?> 
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div style="margin-top:270px"><?php the_content(); ?></div>
   <?php endwhile; endif; ?>          
<?php get_footer(); ?>