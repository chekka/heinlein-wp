<?php
   $headerimg  = get_field('headerimage');
   $head_img_m = get_field('headerimage_mobile');
   if(!$head_img_m){ $head_img_m = $headerimg; }
   $headervid  = get_field('headervideo_url');
   $headertxt  = get_field('headertext');
   $headertop  = get_field('headertext_top');
?>

<?php get_header(); ?> 
   <style>
      .product--header { 
         @media (max-width:580px){
            background-image:url('<?php echo wp_get_attachment_image_url( $head_img_m, 'header-mobile' ); ?>'); 
         }
         @media (min-width:580.1px){
            background-image:url('<?php echo wp_get_attachment_image_url( $headerimg, 'header' ); ?>'); 
         }
      }
   </style>
   <div class="product--header">
   <?php 
      if( have_rows('headericon') ):
         while( have_rows('headericon') ): the_row();
            $icon = get_sub_field('icon');
            $icon_top = get_sub_field('icon_position_top');
            $icon_left = get_sub_field('icon_position_left');
            echo wp_get_attachment_image( $icon, 'full', '', ['class' => 'header-icon hidden-mobile', 'alt' => get_the_title(), 'style' => 'margin-left: ' . $icon_left . '; margin-top: ' . $icon_top . ';'] ); 
         endwhile;
      endif;
   ?>
   </div>   
   <div class="content--max" id="produktanfrage">
      <a class="pop-produktanfrage" href="#">
         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 280 300" style="width:280px" xml:space="preserve">
            <path style="fill:#04204C;" d="M244,0H0v217c0,19.9,16.1,36,36,36h197l47,47v-47V36C280,16.1,263.9,0,244,0z"/>
            <polygon points="161.8,105.1 202.5,105.1 202.5,68.6 24.8,68.6 24.8,167.4 161.8,167.4 "/>
            <text transform="matrix(1 0 0 1 29.6037 50.2466)" style="fill:#9aa6bd; font-family:Gotham; font-weight:500; font-size:22px;"><?php echo _e("STARTEN SIE IHRE"); ?></text>
            <text transform="matrix(1 0 0 1 29.6037 94.3288)"><tspan x="0" y="0" style="fill:#FFFFFF; font-family:Gotham; font-weight:500; font-size:22px;"><?php echo _e("INDIVIDUELLE"); ?></tspan><tspan x="0" y="30.7" style="fill:#FFFFFF; font-family:Gotham; font-size:22px;"><?php echo _e("PRODUKT-"); ?></tspan><tspan x="0" y="61.4" style="fill:#FFFFFF; font-family:Gotham; font-size:22px;"><?php echo _e("ANFRAGE"); ?></tspan></text>
            <text transform="matrix(1 0 0 1 29.55 195.5587)"><tspan x="0" y="0" style="fill:#FFFFFF; font-family:'Encode Sans Expanded'; font-size:15px;"><?php echo _e("Wir antworten"); ?></tspan><tspan x="0" y="23" style="fill:#FFFFFF; font-family:'Encode Sans Expanded'; font-size:15px;"><?php echo _e("innerhalb von 24h"); ?></tspan></text>
            <path style="fill:none;stroke:#AA8461;stroke-width:1.6777;" d="M235.6,143c0-13.3-10.7-23.8-23.8-23.8c-13.3,0-24,10.7-24,23.8s10.7,24,23.8,24C224.9,167,235.6,156.3,235.6,143L235.6,143z M207.9,133.3l10.6,9.9l-10.6,9.9"/>
         </svg>
      </a>
   </div>
   <?php the_content(); ?>

<?php
   get_footer();
?>
