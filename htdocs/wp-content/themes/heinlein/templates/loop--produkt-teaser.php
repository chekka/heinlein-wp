<div class="d-flex content--xxxl flex-column flex-lg-row m-auto justify-content-between align-items-center">
<?php 
if ( have_posts() ):
	while ( have_posts() ): the_post();
      $headerimage = get_field( 'headerimage' );
?>
   <a href="<?php echo get_permalink(); ?>" class="loop-item d-flex flex-column bg--ivory-hell-2 mb-4 mb-lg-0">
      <?php echo wp_get_attachment_image( $headerimage, 'more-footer', "", ["class" => "header-image", "alt"=>get_the_title()] ); ?>
      <p class="p-3 ps-4 text-center text-uppercase"><?php the_title(); ?></p>
   </a>
   <?php
   endwhile;
endif;
?>
</div>