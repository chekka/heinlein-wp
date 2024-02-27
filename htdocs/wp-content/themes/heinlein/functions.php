<?php 

// SCRIPTS AND STYLES
function heinlein_scripts_styles(){
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'heinlein', get_template_directory_uri() . '/assets/css/heinlein.css', array(), null, 'all' );
//    wp_enqueue_script( 'heinlein', get_template_directory_uri() . '/assets/js/heinlein-global.js', array(), null, true );  
//    wp_enqueue_script( 'heinlein-menu', get_template_directory_uri() . '/assets/js/heinlein-menu.js', array(), null, true );  
}
add_action('wp_enqueue_scripts', 'heinlein_scripts_styles');

// ADMIN STYLE
function custom_admin_style() {
    wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/assets/css/backend.css' );
}
add_action( 'admin_enqueue_scripts', 'custom_admin_style' );

// SIDEBARS
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

// NAVIGATION
function heinlein_register_nav_menu(){
  register_nav_menus( array(
    'primary-menu' => __( 'Primary Menu', 'heinlein' ),
    'footer-menu'  => __( 'Footer Menu', 'heinlein' ),
  ) );
}
add_action( 'after_setup_theme', 'heinlein_register_nav_menu', 0 );

// Excerpt length
function heinlein_excerpt_length($length){
  return 150;
}
add_filter('excerpt_length', 'heinlein_excerpt_length', 999);





////////////////////////
// Siteorigin Layouts //
////////////////////////

// Register a custom layouts folder location.
function heinlein_layouts_folder( $layout_folders ) {
    $layout_folders[] = get_template_directory() . '/inc/layouts';
    return $layout_folders;
}
add_filter( 'heinlein_panels_local_layouts_directories', 'heinlein_layouts_folder' );


// Siteorigin pagebuilder stuff
include_once('inc/siteorigin-page-builder.php');

// Adds custom color palettes to wp.color picker.
function siteorigin__custom_color_palettes() {

    $heinlein_theme_settings_options = get_option( 'heinlein_theme_settings_option_name' );
    $farbe_0 = $heinlein_theme_settings_options['farbe_0'];
    $farbe_1 = $heinlein_theme_settings_options['farbe_1'];
    $farbe_2 = $heinlein_theme_settings_options['farbe_2'];
    $farbe_3 = $heinlein_theme_settings_options['farbe_3'];
    $farbe_4 = $heinlein_theme_settings_options['farbe_4'];
    $farbe_5 = $heinlein_theme_settings_options['farbe_5'];

	$color_palettes = json_encode(
		array(
			'$farbe_0',
			'$farbe_1',
			'$farbe_2',
			'$farbe_3',
			'$farbe_4',
			'$farbe_5',
		)
	);
	wp_add_inline_script( 'wp-color-picker', 'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $color_palettes . ';' );
}
add_action( 'customize_controls_enqueue_scripts', 'siteorigin__custom_color_palettes' );

/* Custom Field To Any Siteorigin Widget */
add_action( 'in_widget_form', function( $widget, $return, $instance ) {
    $custom_field = isset( $instance['custom_field'] ) ? $instance['custom_field'] : '';
    ?>
    <p>
        <label for="<?php echo $widget->get_field_id( 'custom_field' ); ?>">
            <?php __( 'Custom Field: ', 'heinlein' ); ?>
        </label>
        <input class="widefat" id="<?php echo $widget->get_field_id( 'custom_field' ); ?>" name="<?php echo $widget->get_field_name( 'custom_field' ); ?>" type="text" value="<?php echo esc_attr( $custom_field ); ?>" />
    </p>
    <?php
}, 1, 3 );

add_filter( 'widget_update_callback', function( $instance, $new_instance ) {
    $instance['custom_field'] = ! empty( $new_instance['custom_field'] ) ? strip_tags( $new_instance['custom_field'] ) : '';
    return $instance;
}, 1, 2 );





//////////////////////
// THEME Customizer //
//////////////////////

function heinlein_customize_register( $wp_customize ) {
  
  $wp_customize->add_section( 'sample_custom_controls_section',
    array(
      'title' => __( 'Textfarbe [by D-HT]' ),
      'description' => esc_html__( 'These are an example of Customizer Custom Controls.' ),
      'panel' => '', // Only needed if adding your Section to a Panel
      'priority' => 160, // Not typically needed. Default is 160
      'capability' => 'edit_theme_options', // Not typically needed. Default is edit_theme_options
      'theme_supports' => '', // Rarely needed
      'active_callback' => '', // Rarely needed
      'description_hidden' => 'false', // Rarely needed. Default is False
    )
  );

  $wp_customize->add_setting( 'body_color',
    array(
       'default' => '#333',
       'transport' => 'refresh',
       'sanitize_callback' => 'sanitize_hex_color'
    )
  );

  $wp_customize->add_control( 'body_color',
    array(
       'label' => __( 'Body Text Farbe' ),
       'description' => esc_html__( 'Globale Textfarbe für „body“' ),
       'section' => 'sample_custom_controls_section',
       'priority' => 10, // Optional. Order priority to load the control. Default: 10
       'type' => 'color',
       'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
    )
  );

};
add_action( 'customize_register', 'heinlein_customize_register' );


function heinlein_customize_css(){
    ?>
         <style type="text/css">
             body { color: <?php echo get_theme_mod('body_color', '#000000'); ?>; }
         </style>
    <?php
}
add_action( 'wp_head', 'heinlein_customize_css');



//////////////////
// OPTIONS PAGE //
//////////////////

class heinlein_Options_Panel {

  /**
   * Options panel arguments.
   */
  protected $args = [];

  /**
   * Options panel title.
   */
  protected $title = '';

  /**
   * Options panel slug.
   */
  protected $slug = '';

  /**
   * Option name to use for saving our options in the database.
   */
  protected $option_name = '';

  /**
   * Option group name.
   */
  protected $option_group_name = '';

  /**
   * User capability allowed to access the options page.
   */
  protected $user_capability = '';

  /**
   * Our array of settings.
   */
  protected $settings = [];

  /**
   * Our class constructor.
   */
  public function __construct( array $args, array $settings ) {
      $this->args              = $args;
      $this->settings          = $settings;
      $this->title             = $this->args['title'] ?? esc_html__( 'Options', 'heinlein' );
      $this->slug              = $this->args['slug'] ?? sanitize_key( $this->title );
      $this->option_name       = $this->args['option_name'] ?? sanitize_key( $this->title );
      $this->option_group_name = $this->option_name . '_group';
      $this->user_capability   = $args['user_capability'] ?? 'manage_options';

      add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
      add_action( 'admin_init', [ $this, 'register_settings' ] );
  }

  /**
   * Register the new menu page.
   */
  public function register_menu_page() {
      add_menu_page(
          $this->title,
          $this->title,
          $this->user_capability,
          $this->slug,
          [ $this, 'render_options_page' ]
      );
  }

  /**
   * Register the settings.
   */
  public function register_settings() {
      register_setting( $this->option_group_name, $this->option_name, [
          'sanitize_callback' => [ $this, 'sanitize_fields' ],
          'default'           => $this->get_defaults(),
      ] );

      add_settings_section(
          $this->option_name . '_sections',
          false,
          false,
          $this->option_name
      );

      foreach ( $this->settings as $key => $args ) {
          $type = $args['type'] ?? 'text';
          $callback = "render_{$type}_field";
          if ( method_exists( $this, $callback ) ) {
              $tr_class = '';
              if ( array_key_exists( 'tab', $args ) ) {
                  $tr_class .= 'heinlein-tab-item heinlein-tab-item--' . sanitize_html_class( $args['tab'] );
              }
              add_settings_field(
                  $key,
                  $args['label'],
                  [ $this, $callback ],
                  $this->option_name,
                  $this->option_name . '_sections',
                  [
                      'label_for' => $key,
                      'class'     => $tr_class
                  ]
              );
          }
      }
  }

  /**
   * Saves our fields.
   */
  public function sanitize_fields( $value ) {
      $value = (array) $value;
      $new_value = [];
      foreach ( $this->settings as $key => $args ) {
          $field_type = $args['type'];
          $new_option_value = $value[$key] ?? '';
          if ( $new_option_value ) {
              $sanitize_callback = $args['sanitize_callback'] ?? $this->get_sanitize_callback_by_type( $field_type );
              $new_value[$key] = call_user_func( $sanitize_callback, $new_option_value, $args );
          } elseif ( 'checkbox' === $field_type ) {
              $new_value[$key] = 0;
          }
      }
      return $new_value;
  }

  /**
   * Returns sanitize callback based on field type.
   */
  protected function get_sanitize_callback_by_type( $field_type ) {
      switch ( $field_type ) {
          case 'select':
              return [ $this, 'sanitize_select_field' ];
              break;
          case 'textarea':
              return 'wp_kses_post';
              break;
          case 'checkbox':
              return [ $this, 'sanitize_checkbox_field' ];
              break;
          default:
          case 'text':
              return 'sanitize_text_field';
              break;
      }
  }

  /**
   * Returns default values.
   */
  protected function get_defaults() {
      $defaults = [];
      foreach ( $this->settings as $key => $args ) {
          $defaults[$key] = $args['default'] ?? '';
      }
      return $defaults;
  }

  /**
   * Sanitizes the checkbox field.
   */
  protected function sanitize_checkbox_field( $value = '', $field_args = [] ) {
      return ( 'on' === $value ) ? 1 : 0;
  }

   /**
   * Sanitizes the select field.
   */
  protected function sanitize_select_field( $value = '', $field_args = [] ) {
      $choices = $field_args['choices'] ?? [];
      if ( array_key_exists( $value, $choices ) ) {
          return $value;
      }
  }

  /**
   * Renders the options page.
   */
  public function render_options_page() {
      if ( ! current_user_can( $this->user_capability ) ) {
          return;
      }

      if ( isset( $_GET['settings-updated'] ) ) {
          add_settings_error(
             $this->option_name . '_mesages',
             $this->option_name . '_message',
             esc_html__( 'Settings Saved', 'heinlein' ),
             'updated'
          );
      }

      settings_errors( $this->option_name . '_mesages' );

      ?>
      <div class="wrap">
          <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
          <?php $this->render_tabs(); ?>
          <form action="options.php" method="post" class="heinlein-options-form">
              <?php
                  settings_fields( $this->option_group_name );
                  do_settings_sections( $this->option_name );
                  submit_button( 'Save Settings' );
              ?>
          </form>
      </div>
      <?php
  }

  /**
   * Renders options page tabs.
   */
  protected function render_tabs() {
      if ( empty( $this->args['tabs'] ) ) {
          return;
      }

      $tabs = $this->args['tabs'];
      ?>

      <style>.heinlein-tab-item{ display: none; ?></style>

      <h2 class="nav-tab-wrapper heinlein-tabs"><?php
          $first_tab = true;
          foreach ( $tabs as $id => $label ) {?>
              <a href="#" data-tab="<?php echo esc_attr( $id ); ?>" class="nav-tab<?php echo ( $first_tab ) ? ' nav-tab-active' : ''; ?>"><?php echo ucfirst( $label ); ?></a>
              <?php
              $first_tab = false;
          }
      ?></h2>

      <script>
          ( function() {
              document.addEventListener( 'click', ( event ) => {
                  const target = event.target;
                  if ( ! target.closest( '.heinlein-tabs a' ) ) {
                      return;
                  }
                  event.preventDefault();
                  document.querySelectorAll( '.heinlein-tabs a' ).forEach( ( tablink ) => {
                      tablink.classList.remove( 'nav-tab-active' );
                  } );
                  target.classList.add( 'nav-tab-active' );
                  targetTab = target.getAttribute( 'data-tab' );
                  document.querySelectorAll( '.heinlein-options-form .heinlein-tab-item' ).forEach( ( item ) => {
                      if ( item.classList.contains( `heinlein-tab-item--${targetTab}` ) ) {
                          item.style.display = 'block';
                      } else {
                          item.style.display = 'none';
                      }
                  } );
              } );
              document.addEventListener( 'DOMContentLoaded', function () {
                  document.querySelector( '.heinlein-tabs .nav-tab' ).click();
              }, false );
          } )();
      </script>

      <?php
  }

  /**
   * Returns an option value.
   */
  protected function get_option_value( $option_name ) {
      $option = get_option( $this->option_name );
      if ( ! array_key_exists( $option_name, $option ) ) {
          return array_key_exists( 'default', $this->settings[$option_name] ) ? $this->settings[$option_name]['default'] : '';
      }
      return $option[$option_name];
  }

  /**
   * Renders a text field.
   */
  public function render_text_field( $args ) {
      $option_name = $args['label_for'];
      $value       = $this->get_option_value( $option_name );
      $description = $this->settings[$option_name]['description'] ?? '';
      ?>
          <input
              type="text"
              id="<?php echo esc_attr( $args['label_for'] ); ?>"
              name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
              value="<?php echo esc_attr( $value ); ?>">
          <?php if ( $description ) { ?>
              <p class="description"><?php echo esc_html( $description ); ?></p>
          <?php } ?>
      <?php
  }

  /**
   * Renders a textarea field.
   */
  public function render_textarea_field( $args ) {
      $option_name = $args['label_for'];
      $value       = $this->get_option_value( $option_name );
      $description = $this->settings[$option_name]['description'] ?? '';
      $rows        = $this->settings[$option_name]['rows'] ?? '4';
      $cols        = $this->settings[$option_name]['cols'] ?? '50';
      ?>
          <textarea
              type="text"
              id="<?php echo esc_attr( $args['label_for'] ); ?>"
              rows="<?php echo esc_attr( absint( $rows ) ); ?>"
              cols="<?php echo esc_attr( absint( $cols ) ); ?>"
              name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo esc_attr( $value ); ?></textarea>
          <?php if ( $description ) { ?>
              <p class="description"><?php echo esc_html( $description ); ?></p>
          <?php } ?>
      <?php
  }

  /**
   * Renders a checkbox field.
   */
  public function render_checkbox_field( $args ) {
      $option_name = $args['label_for'];
      $value       = $this->get_option_value( $option_name );
      $description = $this->settings[$option_name]['description'] ?? '';
      ?>
          <input
              type="checkbox"
              id="<?php echo esc_attr( $args['label_for'] ); ?>"
              name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
              <?php checked( $value, 1, true ); ?>
          >
          <?php if ( $description ) { ?>
              <p class="description"><?php echo esc_html( $description ); ?></p>
          <?php } ?>
      <?php
  }

  /**
   * Renders a select field.
   */
  public function render_select_field( $args ) {
      $option_name = $args['label_for'];
      $value       = $this->get_option_value( $option_name );
      $description = $this->settings[$option_name]['description'] ?? '';
      $choices     = $this->settings[$option_name]['choices'] ?? [];
      ?>
          <select
              id="<?php echo esc_attr( $args['label_for'] ); ?>"
              name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
          >
              <?php foreach ( $choices as $choice_v => $label ) { ?>
                  <option value="<?php echo esc_attr( $choice_v ); ?>" <?php selected( $choice_v, $value, true ); ?>><?php echo esc_html( $label ); ?></option>
              <?php } ?>
          </select>
          <?php if ( $description ) { ?>
              <p class="description"><?php echo esc_html( $description ); ?></p>
          <?php } ?>
      <?php
  }

}


// Register new Options panel.
$panel_args = [
  'title'           => 'My Options',
  'option_name'     => 'my_options',
  'slug'            => 'my-options-panel',
  'user_capability' => 'manage_options',
  'tabs'            => [
    'tab-1' => esc_html__( 'Tab 1', 'heinlein' ),
    'tab-2' => esc_html__( 'Tab 2', 'heinlein' ),
  ],
];

$panel_settings = [
  // Tab 1
  'option_1' => [
    'label'       => esc_html__( 'Checkbox Option', 'heinlein' ),
    'type'        => 'checkbox',
    'description' => 'My checkbox field description.',
    'tab'         => 'tab-1',
  ],
  'content_width' => [
    'label'       => esc_html__( 'Content Breite', 'heinlein' ),
    'type'        => 'text',
    'description' => 'Breite des inneren containers in px',
    'tab'         => 'tab-1',
  ],
  // Tab 2
  'option_3' => [
    'label'       => esc_html__( 'Text Option', 'heinlein' ),
    'type'        => 'text',
    'description' => 'My field 1 description.',
    'tab'         => 'tab-2',
  ],
  'option_4' => [
    'label'       => esc_html__( 'Textarea Option', 'heinlein' ),
    'type'        => 'textarea',
    'description' => 'My textarea field description.',
    'tab'         => 'tab-2',
  ],
];
new heinlein_Options_Panel( $panel_args, $panel_settings );

function heinlein_content_width(){
    $content_width = get_option('content_width');
    if ($content_width != ''){
        echo $content_width;
    }
}





/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

 class heinleinThemeSettings {
	private $heinlein_theme_settings_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'heinlein_theme_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'heinlein_theme_settings_page_init' ) );
	}

	public function heinlein_theme_settings_add_plugin_page() {
		add_theme_page(
			'heinlein Theme Settings', // page_title
			'heinlein Theme Settings', // menu_title
			'manage_options', // capability
			'heinlein-theme-settings', // menu_slug
			array( $this, 'heinlein_theme_settings_create_admin_page' ) // function
		);
	}

	public function heinlein_theme_settings_create_admin_page() {
		$this->heinlein_theme_settings_options = get_option( 'heinlein_theme_settings_option_name' ); ?>

		<div class="wrap">
			<h2>heinlein Theme Settings</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'heinlein_theme_settings_option_group' );
					do_settings_sections( 'heinlein-theme-settings-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function heinlein_theme_settings_page_init() {
		register_setting(
			'heinlein_theme_settings_option_group', // option_group
			'heinlein_theme_settings_option_name', // option_name
			array( $this, 'heinlein_theme_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'heinlein_theme_settings_setting_section', // id
			'Settings', // title
			array( $this, 'heinlein_theme_settings_section_info' ), // callback
			'heinlein-theme-settings-admin' // page
		);

		add_settings_field(
			'farbe_0', // id
			'Farbe', // title
			array( $this, 'farbe_0_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);

		add_settings_field(
			'farbe_1', // id
			'Farbe', // title
			array( $this, 'farbe_1_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);

		add_settings_field(
			'farbe_2', // id
			'Farbe', // title
			array( $this, 'farbe_2_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);

		add_settings_field(
			'farbe_3', // id
			'Farbe', // title
			array( $this, 'farbe_3_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);

		add_settings_field(
			'farbe_4', // id
			'Farbe', // title
			array( $this, 'farbe_4_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);

		add_settings_field(
			'farbe_5', // id
			'Farbe', // title
			array( $this, 'farbe_5_callback' ), // callback
			'heinlein-theme-settings-admin', // page
			'heinlein_theme_settings_setting_section' // section
		);
	}

	public function heinlein_theme_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['farbe_0'] ) ) {
			$sanitary_values['farbe_0'] = sanitize_text_field( $input['farbe_0'] );
		}

		if ( isset( $input['farbe_1'] ) ) {
			$sanitary_values['farbe_1'] = sanitize_text_field( $input['farbe_1'] );
		}

		if ( isset( $input['farbe_2'] ) ) {
			$sanitary_values['farbe_2'] = sanitize_text_field( $input['farbe_2'] );
		}

		if ( isset( $input['farbe_3'] ) ) {
			$sanitary_values['farbe_3'] = sanitize_text_field( $input['farbe_3'] );
		}

		if ( isset( $input['farbe_4'] ) ) {
			$sanitary_values['farbe_4'] = sanitize_text_field( $input['farbe_4'] );
		}

		if ( isset( $input['farbe_5'] ) ) {
			$sanitary_values['farbe_5'] = sanitize_text_field( $input['farbe_5'] );
		}

		return $sanitary_values;
	}

	public function heinlein_theme_settings_section_info() {
		
	}

	public function farbe_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_0]" id="farbe_0" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_0'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_0']) : ''
		);
	}

	public function farbe_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_1]" id="farbe_1" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_1'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_1']) : ''
		);
	}

	public function farbe_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_2]" id="farbe_2" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_2'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_2']) : ''
		);
	}

	public function farbe_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_3]" id="farbe_3" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_3'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_3']) : ''
		);
	}

	public function farbe_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_4]" id="farbe_4" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_4'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_4']) : ''
		);
	}

	public function farbe_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="heinlein_theme_settings_option_name[farbe_5]" id="farbe_5" value="%s">',
			isset( $this->heinlein_theme_settings_options['farbe_5'] ) ? esc_attr( $this->heinlein_theme_settings_options['farbe_5']) : ''
		);
	}

}
if ( is_admin() )
	$heinlein_theme_settings = new heinleinThemeSettings();