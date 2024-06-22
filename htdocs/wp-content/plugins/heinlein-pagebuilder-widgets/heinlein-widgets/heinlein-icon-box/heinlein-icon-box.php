<?php

/*
Widget Name: Heinlein Icon Box
Description: Configurable boxes widget
Author: Johannes Tassilo Gruber
Author URI: https://chekka.de
*/

function heinlein_icon_box_banner_src( $banner_url, $widget_meta ) {
	if( $widget_meta['ID'] == 'heinlein-icon-box') {
		$banner_url = plugin_dir_url(__FILE__) . 'images/icon-box-widget.jpg';
	}
	return $banner_url;
}
add_filter( 'siteorigin_widgets_widget_banner', 'heinlein_icon_box_banner_src', 10, 2);

class heinlein_Icon_Box_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'heinlein-icon-box',
			__('Heinlein Icon Box', 'heinlein-widgets'),
			array(
				'description' => __('Konfigurierbares Boxen Widget', 'heinlein-widgets'),
				'panels_icon' => 'dashicons dashicons-yes-alt',
			),
			array(

			),
			array(
				'icon_orientation' => array(
					'type' => 'radio',
					'label' => __( 'Icon Orientation', 'heinlein-widgets' ),
					'default' => 'flex-column',
					'options' => array(
							'flex-column' =>  __( 'Über dem Text', 'heinlein-widgets' ),
							'flex-column-reverse' =>  __( 'Unter dem Text', 'heinlein-widgets' )
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'icon_orientation' ),
					),
				),
				'icon_position' => array(
					'type' => 'radio',
					'label' => __( 'Icon position', 'heinlein-widgets' ),
					'default' => 'align-items-center',
					'options' => array(
							'icon-left' =>  __( 'Links', 'heinlein-widgets' ),
							'icon-right' =>  __( 'Rechts', 'heinlein-widgets' ),
							'align-items-center' =>  __( 'Zentriert', 'heinlein-widgets' )
					),
					'state_handler' => array(
						'icon_orientation[flex-column]' => array('show'),
						'icon_orientation[flex-row]' => array('hide'),
						'icon_orientation[flex-row-reverse]' => array('hide'),
					),
				),
				'icon_type' => array(
					'type' => 'select',
					'label' => __( '', 'heinlein-widgets' ),
					'default' => 'download',
					'options' => array(
						'download' =>  __( 'Download', 'heinlein-widgets' )
					),
				),
				'icon_color' => array(
					'type' => 'select',
					'label' => __( '', 'heinlein-widgets' ),
					'default' => 'blue',
					'options' => array(
						'blue' =>  __( 'Blau', 'heinlein-widgets' ),
						'gold' =>  __( 'Gold', 'heinlein-widgets' ),
						'white' =>  __( 'Weiß', 'heinlein-widgets' )
					),
				),	
				'media' => array(
					'type' => 'media',
					'label' => __( '', 'heinlein-widgets' ),
					'choose' => __( 'Datei wählen', 'heinlein-widgets' ),
					'update' => __( 'Datei wählen', 'heinlein-widgets' ),
					'library' => 'image,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.ms-word.template.macroEnabled.12,application/vnd.oasis.opendocument.text,application/vnd.apple.pages,application/pdf,application/vnd.ms-xpsdocument,application/oxps,application/rtf,application/wordperfect,application/octet-stream',
					'fallback' => true
				),
				'target_blank' => array(
					'type' => 'checkbox',
					'label' => __( 'In neuem Fenster öffnen', 'heinlein-widgets' ),
        			'default' => true
				),
				'tinymce_editor' => array(
					'type' => 'tinymce',
					'default' => '',
					'rows' => 10,
					'default_editor' => 'html',
					'button_filters' => array(
							'mce_buttons' => array( $this, 'filter_mce_buttons' ),
							'mce_buttons_2' => array( $this, 'filter_mce_buttons_2' ),
							'mce_buttons_3' => array( $this, 'filter_mce_buttons_3' ),
							'mce_buttons_4' => array( $this, 'filter_mce_buttons_5' ),
							'quicktags_settings' => array( $this, 'filter_quicktags_settings' ),
					),
				),
				'section_popup' => array(
					'type' => 'section',
					'label' => __( 'Popup' , 'heinlein-widgets' ),
					'hide' => true,
					'fields' => array(
						'popup_button' => array(
							'type' => 'checkbox',
							'label' => __( 'Is Popup', 'heinlein-widgets' ),
							'default' => false,
							'state_emitter' => array(
								'callback' => 'conditional',
								'args'     => array(
									'popup[yes]: val',
									'popup[no]: ! val',
								),
							),
						),
						'popup_content' => array(
							'type' => 'tinymce',
							'default' => '',
							'rows' => 10,
							'default_editor' => 'html',
							'button_filters' => array(
									'mce_buttons' => array( $this, 'filter_mce_buttons' ),
									'mce_buttons_2' => array( $this, 'filter_mce_buttons_2' ),
									'mce_buttons_3' => array( $this, 'filter_mce_buttons_3' ),
									'mce_buttons_4' => array( $this, 'filter_mce_buttons_5' ),
									'quicktags_settings' => array( $this, 'filter_quicktags_settings' ),
							),
							'state_handler' => array(
								'popup[yes]' => array( 'show' ),
								'popup[no]' => array( 'hide' ),
							),
						),
					),
				),
				'section_btn' => array(
					'type' => 'section',
					'label' => __( 'Button' , 'heinlein-widgets' ),
					'hide' => true,
					'fields' => array(
						'button_text' => array(
							'type' => 'text',
							'placeholder' => 'Button Text'
						),
						'button_ziel' => array(
							'type' => 'link',
							'placeholder' => 'Button Ziel (URL)'
						),
						'button_type' => array(
							'type' => 'radio',
							'label' => __( 'Button Typ', 'heinlein-widgets' ),
							'default' => 'button-solid',
							'options' => array(
									'lvw-btn lvw-btn-primary' =>  __( 'Button solid', 'heinlein-widgets' ),
									'text-arrow' =>  __( 'Text mit Pfeil', 'heinlein-widgets' )
							),
						),
					),
				),
			),
			plugin_dir_path(__FILE__)
		);
	}

	function initialize() {
		parent::initialize();
		$this->register_frontend_styles(
			array(
				array(
					'sow-heinlein-icon-box',	plugin_dir_url( __FILE__ ) . 'styles/heinlein-icon-box.css',
				),
			)
		);
		$this->register_frontend_scripts(
			array(
				array(
					'sow-heinlein-icon-box',	plugin_dir_url( __FILE__ ) . 'scripts/heinlein-icon-box.js',
				),
			)
		);
	}

	function get_template_name($instance) {
		return 'heinlein-icon-box-template';
	}

	function get_style_name($instance) {
	  return 'heinlein-icon-box-style';
	}
}

siteorigin_widget_register('heinlein-icon-box', __FILE__, 'heinlein_Icon_Box_Widget');