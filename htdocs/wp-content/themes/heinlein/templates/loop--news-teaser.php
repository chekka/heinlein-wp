<div class="news-loop d-flex m-auto justify-content-between align-items-start">
<?php 
if ( have_posts() ):
	while ( have_posts() ): the_post();
      $headerimage = get_field( 'headerimage' );
?>
   <a href="<?php echo get_permalink(); ?>" class="loop-item d-flex flex-column bg--blau-light-1 rounded-2-0x0x mb-4">
      <?php echo wp_get_attachment_image( $headerimage, '580-240', "", ["class" => "header-image", "alt"=>get_the_title()] ); ?>
      <div>
         <h2><?php the_title(); ?></h2>
         <div class="excerpt">
            <?php the_excerpt(); ?>
         </div>
      </div>
      <div class="news-item-footer">
         <svg viewBox="0 0 26.589999 26.589999">
            <g transform="matrix(0,-1,1,0,-490.49578,424.72357)">
               <path d="m 411.42857,516.71078 c 7.13625,0 12.92,-5.78375 12.92,-12.92 0,-7.135 -5.78375,-12.92 -12.92,-12.92 -7.13625,0 -12.92,5.785 -12.92,12.92 0,7.13625 5.78375,12.92 12.92,12.92 z" style="stroke-width:0.75;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"></path>
               <path d="m 411.49081,496.94616 0,13.265 m 4.5675,-4.81375 -4.5675,4.81375 -0.91419,-0.964 -0.13819,-0.14573 -3.51262,-3.70402" style="stroke-width:0.75;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"></path>
            </g>
         </svg>
      </div>
   </a>
   <?php
   endwhile;
endif;
?>
</div>