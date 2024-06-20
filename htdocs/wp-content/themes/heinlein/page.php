<?php
   $headerimg = get_field('headerimage');
   $headervid = get_field('headervideo_url');
   $headertxt = get_field('headertext');
   $headertop = get_field('headertext_top');
?>

<?php get_header(); ?> 
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      
      <div id="page-header">
         <?php if($headerimg > 0): echo wp_get_attachment_image( $headerimg, 'page-teaser', "", ["class" => "header-image", "alt"=>get_the_title()] ); elseif($headervid != ""): ?>
         <video class="header-video" muted autoplay loop playsinline>
            <source src="<?php echo $headervid; ?>" type="video/mp4">
         </video>
         <?php endif; ?>
         <?php if($headertxt != ""): ?>
         <div class="header-text" style="margin-top:<?php echo $headertop; ?>">
            <?php the_field('headertext'); ?>
         </div>
         <?php endif; ?>
      </div>
      <?php the_content(); ?>
   <?php endwhile; endif; ?>          
<?php get_footer(); ?>