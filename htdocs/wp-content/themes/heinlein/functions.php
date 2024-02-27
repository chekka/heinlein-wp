<?php 
//
//
// SCRIPTS AND STYLES
//
function heinlein_scripts_styles(){
    // ENQUEUE Styles
    wp_enqueue_style( 'heinlein', get_template_directory_uri() . '/assets/css/heinlein.css', array(), null, 'all' );

    // ENQUEUE Scripts
    wp_enqueue_script('jquery');    
//    wp_enqueue_script( 'heinlein', get_template_directory_uri() . '/assets/js/heinlein-global.js', array(), null, true );  
//    wp_enqueue_script( 'heinlein-menu', get_template_directory_uri() . '/assets/js/heinlein-menu.js', array(), null, true );  

    // DEQUEUE Styles
    wp_dequeue_style( 'contact-form-7' );
    wp_dequeue_style( 'wp-smartcrop' );
}
add_action('wp_enqueue_scripts', 'heinlein_scripts_styles');
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
      $items .= '<li class="separator"><a href="tel: +49 (0) 981 950 20"> <span class="link-description">+49 (0) 981 950 20</span></a></li>';
      $items .= '<li class="menu-item"> <a href="https://www.heinlein-plastik.de/" title="+49 (0) 981 950 20" class="nav-link">Telefon</a></li>';
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
