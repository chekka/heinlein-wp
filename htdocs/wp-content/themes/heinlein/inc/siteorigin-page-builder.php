<?php

/**
 * Register a custom layouts folder location.
 */
function starter_theme_layouts_folder( $layout_folders ) {
  $layout_folders[] = get_template_directory() . '/inc/layouts';
  return $layout_folders;
}
add_filter( 'siteorigin_panels_local_layouts_directories', 'starter_theme_layouts_folder' );

/**
 *  Pagebuilder Row Color
 */
function color_h1_style( $fields ) {
  $fields['h1-color'] = array(
    'name' => __( 'Farbe H1', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'h1-dark' => __( 'Dunkel', 'vhm' ),
      'h1-yellow' => __( 'Gelb', 'vhm' ),
      'h1-pink' => __( 'Pink', 'vhm' ),
      'h1-orange' => __( 'Orange', 'vhm' ),
      'h1-olive' => __( 'Olive', 'vhm' ),
      'h1-blue' => __( 'Blau', 'vhm' ),
      'h1-lightgray' => __( 'Hellgrau', 'vhm' ),
      'h1-white' => __( 'Weiß', 'vhm' )
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 1,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'color_h1_style' );


function color_h2_style( $fields ) {
  $fields['h2-color'] = array(
    'name' => __( 'Farbe H2', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'h2-dark' => __( 'Dunkel', 'vhm' ),
      'h2-yellow' => __( 'Gelb', 'vhm' ),
      'h2-pink' => __( 'Pink', 'vhm' ),
      'h2-orange' => __( 'Orange', 'vhm' ),
      'h2-olive' => __( 'Olive', 'vhm' ),
      'h2-blue' => __( 'Blau', 'vhm' ),
      'h2-lightgray' => __( 'Hellgrau', 'vhm' ),
      'h2-white' => __( 'Weiß', 'vhm' )
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 2,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'color_h2_style' );


function color_h3_style( $fields ) {
  $fields['h3-color'] = array(
    'name' => __( 'Farbe H3', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'h3-dark' => __( 'Dunkel', 'vhm' ),
      'h3-yellow' => __( 'Gelb', 'vhm' ),
      'h3-pink' => __( 'Pink', 'vhm' ),
      'h3-orange' => __( 'Orange', 'vhm' ),
      'h3-olive' => __( 'Olive', 'vhm' ),
      'h3-blue' => __( 'Blau', 'vhm' ),
      'h3-lightgray' => __( 'Hellgrau', 'vhm' ),
      'h3-white' => __( 'Weiß', 'vhm' )
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 3,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'color_h3_style' );


function color_p_style( $fields ) {
  $fields['p-color'] = array(
    'name' => __( 'Farbe Absatz', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'p-dark' => __( 'Dunkel', 'vhm' ),
      'p-yellow' => __( 'Gelb', 'vhm' ),
      'p-pink' => __( 'Pink', 'vhm' ),
      'p-orange' => __( 'Orange', 'vhm' ),
      'p-olive' => __( 'Olive', 'vhm' ),
      'p-blue' => __( 'Blau', 'vhm' ),
      'p-lightgray' => __( 'Hellgrau', 'vhm' ),
      'p-white' => __( 'Weiß', 'vhm' )
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 4,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'color_p_style' );

function color_bg_style( $fields ) {
  $fields['p-color'] = array(
    'name' => __( 'Farbe Hintergrund', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'bg-dark' => __( 'Dunkel', 'vhm' ),
      'bg-yellow' => __( 'Gelb', 'vhm' ),
      'bg-pink' => __( 'Pink', 'vhm' ),
      'bg-orange' => __( 'Orange', 'vhm' ),
      'bg-olive' => __( 'Olive', 'vhm' ),
      'bg-blue' => __( 'Blau', 'vhm' ),
      'bg-lightgray' => __( 'Hellgrau', 'vhm' ),
      'bg-white' => __( 'Weiß', 'vhm' )
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 5,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'color_bg_style' );

function deco_left( $fields ) {
  $fields['deco-left'] = array(
    'name' => __( 'Deko Element rechts', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'deco-left-yellow' => __( 'Gelb', 'vhm' ),
      'deco-left-pink' => __( 'Pink', 'vhm' ),
      'deco-left-orange' => __( 'Orange', 'vhm' ),
      'deco-left-olive' => __( 'Olive', 'vhm' ),
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 6,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'deco_left' );

function deco_right( $fields ) {
  $fields['deco-right'] = array(
    'name' => __( 'Deko Element links', 'vhm' ),
    'type' => 'select',
    'options' => array(
      '' => __( '-- leer --', 'vhm' ),
      'deco-right-yellow' => __( 'Gelb', 'vhm' ),
      'deco-right-pink' => __( 'Pink', 'vhm' ),
      'deco-right-orange' => __( 'Orange', 'vhm' ),
      'deco-right-olive' => __( 'Olive', 'vhm' ),
    ),
    'group' => 'design',
    'description' => __( '', 'vhm' ),
    'priority' => 6,
  );
  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'deco_right' );

/**
 *  Return Fields
 */
function h1_row_style_attr( $attributes, $args ) {
  if ( ! empty( $args['h1-color'] ) ) {
    array_push( $attributes['class'], $args['h1-color'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'h1_row_style_attr', 10, 2 );

function h2_row_style_attr( $attributes, $args ) {
  if ( ! empty( $args['h2-color'] ) ) {
    array_push( $attributes['class'], $args['h2-color'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'h2_row_style_attr', 10, 2 );


function h3_row_style_attr( $attributes, $args ) {
  if ( ! empty( $args['h3-color'] ) ) {
    array_push( $attributes['class'], $args['h3-color'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'h3_row_style_attr', 10, 2 );


function p_row_style_attr( $attributes, $args ) {
  if ( ! empty( $args['p-color'] ) ) {
    array_push( $attributes['class'], $args['p-color'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'p_row_style_attr', 10, 2 );

function bg_row_style_attr( $attributes, $args ) {
  if ( ! empty( $args['bg-color'] ) ) {
    array_push( $attributes['class'], $args['bg-color'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'bg_row_style_attr', 10, 2 );

function add_deco_left( $attributes, $args ) {
  if ( ! empty( $args['deco-left'] ) ) {
    array_push( $attributes['class'], $args['deco-left'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'add_deco_left', 10, 2 );

function add_deco_right( $attributes, $args ) {
  if ( ! empty( $args['deco-right'] ) ) {
    array_push( $attributes['class'], $args['deco-right'] );
  }
  return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'add_deco_right', 10, 2 );
