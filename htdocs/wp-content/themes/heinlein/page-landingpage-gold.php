<?php /* Template Name: Landingpage Gold */ ?>

<?php
   $headerimg  = get_field('headerimage');
   $head_img_m = get_field('headerimage_mobile');
   if(!$head_img_m){ $head_img_m = $headerimg; }
   $headervid  = get_field('headervideo_url');
   $headertxt  = get_field('headertext');
   $headertop  = get_field('headertext_top');
?>

<?php get_header(); ?> 
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <?php the_content(); ?>
   <?php endwhile; endif; ?>          
<?php get_footer(); ?>
