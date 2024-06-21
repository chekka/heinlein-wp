<?php 
//
//
// SCRIPTS AND STYLES
//
function heinlein_scripts_styles(){
    // ENQUEUE Styles
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/slick/slick.css', array(), null, 'all' );
    wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/assets/slick/slick-theme.css', array(), null, 'all' );
    wp_enqueue_style( 'heinlein', get_template_directory_uri() . '/assets/css/heinlein.css', array(), null, 'all' );
    wp_enqueue_style( 'counter', get_template_directory_uri() . '/assets/css/counter.css', array(), null, 'all' );
    if ( is_front_page() ) :
      wp_enqueue_style( 'product-slider', get_template_directory_uri() . '/assets/css/product-slider.css', array(), null, 'all' );      
    endif;

    // ENQUEUE Scripts
    wp_enqueue_script( 'jquery' );
    if ( is_front_page() ) :
      wp_enqueue_script( 'product-slider', get_template_directory_uri() . '/assets/js/product-slider.js', array(), null, true );
    endif;
    wp_enqueue_script( 'counter', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array(), null, true );
    wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/assets/js/waypoints.min.js', array(), null, true );    
    wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/slick/slick.js', array(), null, true );
    wp_enqueue_script( 'colorbox', get_template_directory_uri() . '/assets/colorbox/jquery.colorbox-min.js', array(), null, true );
    wp_enqueue_script( 'heinlein', get_template_directory_uri() . '/assets/js/heinlein-global.js', array(), null, true );  
    wp_enqueue_script( 'heinlein-menu', get_template_directory_uri() . '/assets/js/heinlein-menu.js', array(), null, true );

    // DEQUEUE Styles
    wp_dequeue_style( 'contact-form-7' );
    wp_dequeue_style( 'wp-smartcrop' );
}
add_action('wp_enqueue_scripts', 'heinlein_scripts_styles');
// 
//
// Tiny MCE Editor Font
//
function heinlein_add_editor_styles() {
  add_editor_style( 'assets/css/tinymce.css' );
}
add_action( 'after_setup_theme', 'heinlein_add_editor_styles' );
// 
//
// SIDEBARS
//
function heinlein_widgets_init() {    
  register_sidebar(array(
    'name'           => __( 'Header', 'heinlein' ),
    'id'             => 'header',
    'class'          => 'site-header',
    'before_widget'  => '<div id="%1$s" class="widget %2$s">',
    'after_widget'   => '</div>',
    'before_title'   => '<div class="widget-title">',
    'after_title'    => '</div>',
		'before_sidebar' => '',
		'after_sidebar'  => '',
  ));
  register_sidebar(array(
    'name'           => __( 'Footer', 'heinlein' ),
    'id'             => 'footer',
    'class'          => 'site-footer',
    'before_widget'  => '<div id="%1$s" class="widget %2$s">',
    'after_widget'   => '</div>',
    'before_title'   => '<div class="widget-title">',
    'after_title'    => '</div>',
		'before_sidebar' => '',
		'after_sidebar'  => '',
  ));
}
add_action('widgets_init', 'heinlein_widgets_init');
//
//
// Media Sizes
//
add_image_size( 'more-footer', 350, 200, true );
add_image_size( 'mainnav@2', 420, 250, true );
add_image_size( 'mainnav', 210, 125, true );
add_image_size( 'mainnav-wide@2', 900, 250, true );
add_image_size( 'mainnav-wide', 450, 125, true );
//
//
// NAVIGATION
//
function heinlein_register_nav_menu(){
  register_nav_menus( array(
    'primary-menu' => __( 'Primary Menu', 'heinlein' ),
    'footer-menu'  => __( 'Footer Menu', 'heinlein' ),
  ) );
}
add_action( 'after_setup_theme', 'heinlein_register_nav_menu', 0 );

/* Add phone to main nav */
function phone_menu_item( $items, $args ) {
  if( $args->theme_location == 'primary-menu' ){
      // $items .= '<li class="separator"><a href="tel: +49 (0) 981 950 20"> <span class="link-description">+49 (0) 981 950 20</span></a></li>';
      // $items .= '<li class="menu-item"> <a href="https://www.heinlein-plastik.de/" title="+49 (0) 981 950 20" class="nav-link">Telefon</a></li>';
  }
  return $items;
}
add_filter( 'wp_nav_menu_items', 'phone_menu_item', 10, 2 );
//
//
// Excerpt length
//
function heinlein_excerpt_length($length){
  return 150;
}
add_filter('excerpt_length', 'heinlein_excerpt_length', 999);


function deregister_media_elements(){
  wp_deregister_script('wp-mediaelement');
  wp_deregister_style('wp-mediaelement');
}
add_action('wp_enqueue_scripts','deregister_media_elements');

// Add shortcodes
add_filter( 'wpcf7_form_elements', 'do_shortcode' );
add_shortcode( 'page_title', 'get_the_title' );

function print_required_span() {
  return '<span class="required">*</span>';
}
add_shortcode('*', 'print_required_span');

// Do not resize images 
add_filter( 'big_image_size_threshold', '__return_false' ); 