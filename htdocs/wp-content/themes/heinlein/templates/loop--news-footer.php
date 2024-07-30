<div class="post-loop d-flex content--xxxl flex-column flex-lg-row m-auto justify-content-between align-items-center">
<?php 
if ( have_posts() ):
	while ( have_posts() ): the_post();
      if(get_field( 'vorschaubild' ) != ""):
         $image = get_field( 'vorschaubild' );
      else:
         $image = get_field( 'headerimage' );
      endif;
      
?>
   <a href="<?php echo get_permalink(); ?>" class="loop-item d-flex flex-column bg--white shadowed mb-4 mb-lg-0">
      <?php echo wp_get_attachment_image( $image, 'more-footer', "", ["class" => "header-image", "alt"=>get_the_title()] ); ?>
      <p class="p-3 ps-4 text-center text-uppercase"><?php the_title(); ?></p>
   </a>
   <?php
   endwhile;
endif;
?>
</div>