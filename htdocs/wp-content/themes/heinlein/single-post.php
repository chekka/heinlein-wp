<?php
   $headerimg = get_field('headerimage');
   $head_img_m = get_field('headerimage_mobile');
   if(!$head_img_m){ $head_img_m = $headerimg; }
   $headervid = get_field('headervideo_url');
   $headertxt = get_field('headertext');
   $headertop = get_field('headertext_top');
?>

<?php get_header(); ?> 
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      
      <style>         
         @media ( max-width: 580px ){
            #page-header { 
               <?php if($head_img_m > 0): ?>
               background-image:url('<?php echo wp_get_attachment_image_url( $head_img_m, 'header-mobile' ); ?>'); 
               <?php else: ?>
               background-image:url('<?php echo wp_get_attachment_image_url( $headerimg, 'header-mobile' ); ?>'); 
               <?php endif; ?>
               background-position: center center;
            }
         }
         @media ( min-width: 580.1px ){
            #page-header { 
               background-image: url( '<?php echo wp_get_attachment_image_url( $headerimg, 'header' ); ?>' ); 
               background-position: center center;
            }
         }
      </style>
      <div id="page-header">
         <?php if($headervid != ""): ?>
         <video class="header-video" muted autoplay loop playsinline>
            <source src="<?php echo $headervid; ?>" type="video/mp4">
         </video>
         <?php endif; ?>
         <?php if($headertxt != ""): ?>
         <div class="header-text" style="margin-top:<?php echo $headertop; ?>">
            <?php the_field('headertext'); ?>
         </div>
         <?php endif; ?>
         <?php 
            if( have_rows('headericon') ):
               while( have_rows('headericon') ): the_row();
                  $icon = get_sub_field('icon');
                  $icon_css = get_sub_field('icon_css');
                  $icon_top = get_sub_field('icon_position_top');
                  $icon_left = get_sub_field('icon_position_left');                  
                  echo wp_get_attachment_image( $icon, 'full', '', ['class' => 'header-icon', 'alt' => get_the_title(), 'style' => 'margin-left: ' . $icon_left . '; margin-top: ' . $icon_top . ';' . $icon_css] ); 
               endwhile;
            endif;
         ?>
      </div>
      <?php the_content(); ?>
   <?php endwhile; endif; ?> 
<?php get_footer(); ?>