<?php
/*
Plugin Name: SiteOrigin Link Overlay
Description: Link an entire Page Builder row, column, or widget.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/link-overlay
Tags: Page Builder
Requires: siteorigin-panels
*/

class SiteOrigin_Premium_Plugin_Link_Overlay {
	public function __construct() {
		add_filter( 'siteorigin_panels_row_style_groups', array( $this, 'add_style_group' ), 10, 3 );
		add_filter( 'siteorigin_panels_cell_style_groups', array( $this, 'add_style_group' ), 10, 3 );
		add_filter( 'siteorigin_panels_widget_style_groups', array( $this, 'add_style_group' ), 10, 3 );
		add_filter( 'siteorigin_panels_row_style_fields', array( $this, 'add_style_fields' ), 10, 3 );
		add_filter( 'siteorigin_panels_cell_style_fields', array( $this, 'add_style_fields' ), 10, 3 );
		add_filter( 'siteorigin_panels_widget_style_fields', array( $this, 'add_style_fields' ), 10, 3 );

		add_filter( 'siteorigin_panels_inside_row_after', array( $this, 'add_link_overlay_html' ), 10, 2 );
		add_filter( 'siteorigin_panels_inside_cell_after', array( $this, 'add_link_overlay_html' ), 10, 2 );
		add_filter( 'siteorigin_panels_inside_widget_after', array( $this, 'add_link_overlay_html' ), 10, 2 );
		add_filter( 'siteorigin_panels_css_object', array( $this, 'add_link_overlay_css' ), 10, 4 );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function add_style_group( $groups, $post_id, $args ) {
		$groups['link_overlay'] = array(
			'name' => __( 'Link Overlay', 'siteorigin-premium' ),
			'priority' => 25,
		);

		return $groups;
	}

	public function add_style_fields( $fields, $post_id, $args ) {
		$fields['link_overlay_url'] = array(
			'name' => __( 'URL', 'siteorigin-premium' ),
			'type' => 'url',
			'group' => 'link_overlay',
			'priority' => 10,
		);
		$fields['link_overlay_new_window'] = array(
			'name' => '',
			'label' => __( 'Open link in new window', 'siteorigin-premium' ),
			'type' => 'checkbox',
			'group' => 'link_overlay',
			'priority' => 20,
		);

		$fields['link_overlay_label'] = array(
			'name' => __( 'Accessibility Label', 'siteorigin-premium' ),
			'type' => 'text',
			'description' => __( 'Describe the link for screen readers.', 'siteorigin-premium' ),
			'group' => 'link_overlay',
			'priority' => 30,
		);

		return $fields;
	}

	/**
	 * Determine if the current item has a link overlay enabled.
	 *
	 * @return bool
	 **/
	private function has_link_overlay( $data ) {
		return ! empty( $data ) &&
			(
				! empty( $data['style'] ) &&
				! empty( $data['style']['link_overlay_url'] )
			) ||
			(
				! empty( $data['panels_info'] ) &&
				! empty( $data['panels_info']['style'] ) &&
				! empty( $data['panels_info']['style']['link_overlay_url'] )
			);
	}

	public function add_link_overlay_html( $content, $data ) {
		if (
			$this->has_link_overlay( $data )
		) {
			$content .= '<a href="' . esc_url( $data['style']['link_overlay_url'] ) . '"';

			if ( ! empty( $data['style']['link_overlay_new_window'] ) ) {
				$content .= ' target="_blank" rel="noopener noreferrer"';
			}
			
			if ( ! empty( $data['style']['link_overlay_label'] ) ) {
				$content .= ' aria-label="' . esc_attr( $data['style']['link_overlay_label'] ) . '"';
			}
			$content .= ' class="so-premium-link-overlay">&nbsp;</a>';
		}

		return $content;
	}

	public function add_link_overlay_css( $css, $panels_data, $post_id, $layout_data ) {
		$add_css = false;

		// Check for any rows, cells, or widgets that have a link overlay aded.
		foreach ( $layout_data as $row ) {
			if ( $this->has_link_overlay( $row ) ) {
				$add_css = true;
				break;
			}

			foreach ( $row['cells'] as $cell ) {
				if ( $this->has_link_overlay( $cell ) ) {
					$add_css = true;
					break;
				}

				foreach ( $cell['widgets'] as $widget ) {
					if ( $this->has_link_overlay( $widget ) ) {
						$add_css = true;
						break;
					}
				}
			}
		}

		if ( $add_css ) {
			$css->add_css(
				'.so-premium-link-overlay',
				array(
					'border' => '0 !important',
					'bottom' => 0,
					'color' => 'transparent !important',
					'left' => 0,
					'position' => 'absolute',
					'right' => 0,
					'text-decoration' => 'none !important',
					'top' => 0,
					'z-index' => 101, // 101 is to ensure it's higher than the our sliders.
				)
			);

			// The container of the link overlay needs to be positioned relative to allow for the overlay to work.
			$css->add_css(
				'.panel-grid, .panel-grid-cell, .so-panel',
				array(
					'position' => 'relative',
				)
			);
		}

		return $css;
	}
}
