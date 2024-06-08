<div class="d-flex bg--ivory-dunkel content--max">
<?php 
if ( have_posts() ):
	while ( have_posts() ): the_post();
      $headerimage = get_field( 'headerimage' );
?>
   <div class="loop-item">
      <a href="<?php echo get_permalink(); ?>">
      <?php      
         echo wp_get_attachment_image( $headerimage, 'more-footer', "", ["class" => "header-image", "alt"=>get_the_title()] );
         the_title();
      ?>
      </a>
   </div>
<?php
   endwhile;
endif;
?>
</div>