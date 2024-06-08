<?php
   $headerimage = get_field( 'headerimage' );
   $icon = get_field( 'icon' );

   get_header();   
?>

   <div class="product--header">
   <?php
      if( have_rows('header_icon') ):

         while( have_rows('header_icon') ): the_row();
         $icon = get_sub_field('icon');
         $icon_top = get_sub_field('icon_position_top');
         $icon_left = get_sub_field('icon_position_left');
         
         echo wp_get_attachment_image( $icon, 'full', '', ['class' => 'header-icon', 'alt' => get_the_title(), 'style' => 'left:' . $icon_left . ';top:' . $icon_top] );

         endwhile;
      endif;
      ?>
      <?php echo wp_get_attachment_image( $headerimage, 'full', "", ["class" => "header-image", "alt"=>get_the_title()] ); ?>
   </div>   
   <div class="content--max" id="produktanfrage">
      <a class="pop-produktanfrage" href="#">
         <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 280 300" style="width:280px" xml:space="preserve">
            <path style="fill:#04204C;" d="M244,0H0v217c0,19.9,16.1,36,36,36h197l47,47v-47V36C280,16.1,263.9,0,244,0z"/>
            <polygon style="fill:#AA8461;" points="161.8,105.1 202.5,105.1 202.5,68.6 24.8,68.6 24.8,167.4 161.8,167.4 "/>
            <rect x="29.6" y="34.2" style="fill:none;" width="250.4" height="56.2"/>
            <text transform="matrix(1 0 0 1 29.6037 50.2466)" style="fill:#9aa6bd; font-family:Gotham; font-weight:500; font-size:22px;">STARTEN SIE IHRE</text>
            <rect x="29.6" y="78.3" style="fill:none;" width="250.4" height="103.2"/>
            <text transform="matrix(1 0 0 1 29.6037 94.3288)"><tspan x="0" y="0" style="fill:#FFFFFF; font-family:Gotham; font-weight:500; font-size:22px;">INDIVIDUELLE</tspan><tspan x="0" y="30.7" style="fill:#FFFFFF; font-family:'Gotham-Medium'; font-size:22px;">PRODUKT-</tspan><tspan x="0" y="61.4" style="fill:#FFFFFF; font-family:'Gotham-Medium'; font-size:22px;">ANFRAGE</tspan></text>
            <rect x="29.5" y="183.4" style="fill:none;" width="250.5" height="53.4"/>
            <text transform="matrix(1 0 0 1 29.55 195.5587)"><tspan x="0" y="0" style="fill:#FFFFFF; font-family:'Encode Sans Expanded'; font-size:15px;">Wir antworten </tspan><tspan x="0" y="23" style="fill:#FFFFFF; font-family:'EncodeSansExpanded-Regular'; font-size:15px;">innerhalb von 24h</tspan></text>
            <path style="fill:none;stroke:#AA8461;stroke-width:1.6777;" d="M235.6,143c0-13.3-10.7-23.8-23.8-23.8c-13.3,0-24,10.7-24,23.8s10.7,24,23.8,24C224.9,167,235.6,156.3,235.6,143L235.6,143z M207.9,133.3l10.6,9.9l-10.6,9.9"/>
         </svg>
      </a>
   </div>
   <?php the_content(); ?>

<?php
   get_footer();
?>
