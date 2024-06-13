<?php

/*
Plugin Name: Heinlein SiteOrigin Widgets
Description: Plugin to extend the SiteOrigin Widgets Bundle for Heinelein Plastik.
Version: 0.1
Author: Johannes Tassilo Gruber
Author URI: https://chekka.de
License: only for use at heinelein-plastik.de
*/

function heinlein_widgets_collection($folders){
	$folders[] = plugin_dir_path(__FILE__).'heinlein-widgets/';
	return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'heinlein_widgets_collection');

function heinlein_fields_class_prefixes( $class_prefixes ) {
	$class_prefixes[] = 'My_Custom_Field_';
	return $class_prefixes;
}
add_filter( 'siteorigin_widgets_field_class_prefixes', 'heinlein_fields_class_prefixes' );

function heinlein_fields_class_paths( $class_paths ) {
	$class_paths[] = plugin_dir_path( __FILE__ ) . 'custom-fields/';
	return $class_paths;
}
add_filter( 'siteorigin_widgets_field_class_paths', 'heinlein_fields_class_paths' );

/* Pagebuilder Widget Group */
function heinlein_add_widget_tabs($tabs) {
	$tabs[] = array(
		'title' => __('Heinlein Widgets', 'heinlein-widgets'),
		'filter' => array(
				'groups' => array('heinlein_widgets')
		)
	);
	return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'heinlein_add_widget_tabs', 20);

/* Add widgets to LV widget group */
function heinlein_widget_group( $widgets ) {
	$widgets['heinlein_Image_Box_Widget']['groups'] = array('heinlein_widgets');
	$widgets['heinlein_Icon_Box_Widget']['groups'] = array('heinlein_widgets');
	$widgets['heinlein_Header_Widget']['groups'] = array('heinlein_widgets');
	return $widgets;
}
add_filter( 'siteorigin_panels_widgets', 'heinlein_widget_group', 12 );