<?php

class SiteOrigin_Widget_ContactForm_Field_Builder extends SiteOrigin_Widget_ContactForm_Field_Base {
	protected function render_field( $options ) {
		if ( function_exists( 'siteorigin_panels_render' ) ) {
			echo siteorigin_panels_render(
				'w' . $options['field']['field_name'],
				true,
				$options['field']['builder_options']
			);
		} else {
			esc_html_e( 'This widget requires Page Builder.', 'siteorigin-premium' );
		}
	}
}
