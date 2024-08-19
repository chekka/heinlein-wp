<?php
/*
Plugin Name: Related Posts
Description: Display related posts at the end of your content, enhancing user engagement by showcasing relevant articles based on categories, tags, or similar titles.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/related-posts
Tags: Widgets Bundle
Requires: so-widgets-bundle/blog
Minimum Version: so-widgets-bundle 1.62.3
*/

class SiteOrigin_Premium_Plugin_Related_Posts {
	public function __construct() {
		if ( defined( 'SOW_BUNDLE_VERSION' ) ) {
			add_filter( 'siteorigin_premium_metabox_form_options', array( $this, 'metabox_options' ), 11, 1 );
			add_filter( 'the_content', array( $this, 'add_related_posts' ), 13, 1 );
		}
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	public function get_settings_form() {
		if ( version_compare( SOW_BUNDLE_VERSION, '1.62.3', '>=' ) ) {
			$settings = array(
				'types' => array(
					'type' => 'checkboxes',
					'label' => __( 'Enabled Post Types', 'siteorigin-premium' ),
					'default' => array(
						'post',
					),
					'options' => SiteOrigin_Premium_Utility::single()->get_post_types(),
				),
				'widget' => array(
					'type' => 'widget',
					'label' => __( 'Settings', 'siteorigin-premium' ),
					'class' => 'SiteOrigin_Widget_Blog_Widget',
					'form_filter' => array( $this, 'filter_blog_widget' ),
				),
			);
		} else {
			$settings = array(
				'html' => array(
					'type' => 'html',
					'markup' => sprintf( __( 'This addon requires SiteOrigin Widgets Bundle 1.62.3. You have version %s installed. Please update SiteOrigin Widgets Bundle.', 'siteorigin-premium' ), SOW_BUNDLE_VERSION ),
				),
			);
		}

		if ( ! empty( $settings ) ) {
			return new SiteOrigin_Premium_Form(
				'so-addon-related-posts-settings',
				$settings
			);
		}
	}

	// Remove fields we don't want to be adjustable in the Global Settings.
	public function filter_blog_widget( $form_fields ) {
		// Change defaults.
		$form_fields['settings']['fields']['content']['default'] = 'none';
		$form_fields['template']['default'] = 'grid';
		$form_fields['settings']['label'] = __( 'Template Settings', 'siteorigin-premium' );
		$form_fields['settings']['fields']['columns']['default'] = 3;
		$form_fields['settings']['fields']['tag']['default'] = 'h4';
		$form_fields['settings']['fields']['date']['default'] = false;
		$form_fields['settings']['fields']['author']['default'] = false;
		$form_fields['settings']['fields']['categories']['default'] = false;
		$form_fields['settings']['fields']['tags']['default'] = false;
		$form_fields['settings']['fields']['comment_count']['default'] = false;
		$form_fields['design']['fields']['title']['fields']['font_size']['default'] = '16px';

		// Remove fields.
		unset( $form_fields['template']['options']['standard'] );
		unset( $form_fields['template']['options']['portfolio'] );
		unset( $form_fields['template']['options']['offset'] );
		unset( $form_fields['template']['options']['alternate'] );
		unset( $form_fields['settings']['fields']['content']['options']['full'] );
		unset( $form_fields['settings']['fields']['pagination'] );
		unset( $form_fields['settings']['fields']['pagination_reload'] );
		unset( $form_fields['settings']['fields']['read_more'] );
		unset( $form_fields['design']['fields']['pagination'] );
		unset( $form_fields['design']['fields']['pagination_premium'] );

		// Preset data needs to be restructured due to the Blog widget
		// being used as a sub-widget.
		$options = array( 'grid', 'masonry' );

		foreach ( $options as $option ) {
			$form_fields['template']['options'][ $option ]['values'] = array(
				'widget' => $form_fields['template']['options'][ $option ]['values'],
			);
		}

		// Add fields.
		$form_fields['design']['fields'] = array_merge(
			array(
				'related_title' => array(
					'type' => 'section',
					'label' => __( 'Related Posts Title', 'siteorigin-premium' ),
					'fields' => array(
						'font' => array(
							'type' => 'font',
							'label' => __( 'Font', 'siteorigin-premium' ),
						),
						'font_size' => array(
							'type' => 'measurement',
							'label' => __( 'Font Size', 'siteorigin-premium' ),
							'default' => '16px',
						),
						'color' => array(
							'type' => 'color',
							'label' => __( 'Color', 'siteorigin-premium' ),
							'default' => '#2d2d2d',
						),
						'bottom_margin' => array(
							'type' => 'measurement',
							'label' => __( 'Bottom Margin', 'siteorigin-premium' ),
							'default' => '20px',
						),
					),
				),
				'container' => array(
					'type' => 'section',
					'label' => __( 'Related Posts Container', 'siteorigin-premium' ),
					'fields' => array(
						'margin' => array(
							'type' => 'multi-measurement',
							'label' => __( 'Margin', 'siteorigin-premium' ),
							'autofill' => true,
							'default' => '50px 0px',
							'measurements' => array(
								'top' => array(
									'label' => __( 'Top', 'siteorigin-premium' ),
								),
								'bottom' => array(
									'label' => __( 'Bottom', 'siteorigin-premium' ),
								),
							),
						),
					),
				),
			),
			$form_fields['design']['fields']
		);

		$form_fields['posts']['show_count'] = false;
		$form_fields['posts']['fields'] = array(
			'post_type' => array(
				'remove' => true,
			),
			'post__in' => array(
				'remove' => true,
			),
			'tax_query' => array(
				'remove' => true,
			),
			'tax_query_relation' => array(
				'label' => __( 'Taxonomy Matching', 'siteorigin-premium' ),
				'options' => array(
					'OR' => __( 'ANY', 'siteorigin-premium' ),
					'AND' => __( 'ALL', 'siteorigin-premium' ),
				),
				'description' => __( 'Determines how posts are related based on their taxonomies (categories, tags, or custom groupings). ANY shows posts sharing at least one taxonomy. ALL shows only posts that share all the same taxonomies.', 'siteorigin-premium' ),
			),
			'date_type' => array(
				'remove' => true,
			),
			'date_query' => array(
				'remove' => true,
			),
			'date_query_relative' => array(
				'remove' => true,
			),
			'sticky' => array(
				'default' => 'exclude',
			),
			'posts_per_page' => array(
				'label' => __( 'Number of Related Posts', 'siteorigin-premium' ),
				'default' => 3,
			),
		);

		return $form_fields;
	}

	public function filter_blog_widget_query( $query, $instance ) {
		$post = get_post();

		if ( empty( $post ) ) {
			return $query;
		}

		$addon_query = array();

		// Prevent the current post from being included.
		$addon_query['post__not_in'] = array( $post->ID );

		$addon_query['posts_per_page'] = apply_filters(
			'siteorigin_premium_related_posts_count',
			$query['posts_per_page'] ?? 3
		);

		$taxonomies = get_post_taxonomies( $post->ID );

		if ( ! empty( $taxonomies ) ) {
			$addon_query['tax_query'] = [];

			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms(
					$post->ID,
					$taxonomy,
					array(
						'fields' => 'ids'
					)
				);

				if ( ! empty( $terms ) ) {
					$addon_query['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
						'terms' => $terms,
					);
				}
			}

			if ( ! empty( $addon_query['tax_query'] ) ) {
				$addon_query['tax_query']['relation'] = $query['tax_query_relation'] ?? 'OR';
			}
		}

		$query = apply_filters(
			'siteorigin_premium_related_posts_query',
			array_merge(
				$query,
				$addon_query
			)
		);

		return $query;
	}

	public function modify_blog_instance( $template_variables, $instance, $args, $widget ) {
		if ( empty( $instance ) ) {
			return array();
		}

		$template_variables['settings']['pagination'] = 'disabled';
		$template_variables['settings']['read_more'] = true;

		$remaining_posts = count( $template_variables['posts']->posts ) - (int) $template_variables['posts']->query['posts_per_page'];
		if ( $remaining_posts > 0 ) {
			// There's still room for more posts. Let's fetch some more using the post title.
			$post = get_post();
			$title_search_args = array(
				'post_type' => $post->post_type,
				's' => $post->post_title,
				'posts_per_page' => $remaining_posts,
				'fields' => 'ids',
				'post__not_in' => get_option( 'sticky_posts' ),
			);

			$title_search_query = new WP_Query( $title_search_args );
			$title_search_posts_ids = $title_search_query->posts;

			// Extract post ids from from original query.
			$taxonomy_posts_ids = array_map( function( $post ) {
				return $post->ID;
			}, $template_variables['posts']->posts );

			$combined_posts_ids = array_unique(
				array_merge(
					$taxonomy_posts_ids,
					$title_search_posts_ids
				)
			);

			if ( ! empty( $combined_posts_ids ) ) {
				// Let's re-query the posts so we can display them using the loop.
				$template_variables['posts'] = new WP_Query( array(
					'post_type' => $post->post_type,
					'post__in' => $combined_posts_ids,
					'posts_per_page' => -1,
				) );
			}
		}

		return $template_variables;
	}

	public function add_related_posts_hooks() {
		add_filter( 'siteorigin_widgets_blog_query', array( $this, 'filter_blog_widget_query' ), 15, 2 );
		add_filter( 'siteorigin_widgets_template_variables_sow-blog', array( $this, 'modify_blog_instance' ), 11, 4 );
		add_filter( 'siteorigin_widgets_less_variables_sow-blog', array( $this, 'add_less_variables' ), 1, 3 );
		add_filter( 'siteorigin_widgets_less_vars_sow-blog', array( $this, 'add_less' ), 20, 3 );
		add_filter( 'siteorigin_widgets_blog_content_wrapper_styles', array( $this, 'adjust_content_wrapper_styles' ), 10, 2 );
	}

	public function add_related_posts( $content ) {
		global $wp_current_filter;

		// To prevent a display issue, we don't want to display
		// related posts in the excerpt.
		if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
			return $content;
		}

		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/related-posts' );

		if ( ! SiteOrigin_Premium_Utility::single()->is_addon_enabled_for_post( $settings, 'related_posts' ) ) {
			return $content;
		}

		if ( empty( $settings['widget'] ) ) {
			return $content;
		}

		$this->add_related_posts_hooks();

		ob_start();
		global $wp_widget_factory;
		$the_widget = $wp_widget_factory->widgets['SiteOrigin_Widget_Blog_Widget'];
		$the_widget->widget( array(), $settings['widget'] );

		$this->remove_related_posts_hooks();

		return $content . ob_get_clean();
	}

	public function remove_related_posts_hooks() {
		remove_filter( 'siteorigin_widgets_blog_query', array( $this, 'filter_blog_widget_query' ), 15, 2 );
		remove_filter( 'siteorigin_widgets_template_variables_sow-blog', array( $this, 'modify_blog_instance' ), 11, 4 );
		remove_filter( 'siteorigin_widgets_less_variables_sow-blog', array( $this, 'add_less_variables' ), 1, 3 );
		remove_filter( 'siteorigin_widgets_less_vars_sow-blog', array( $this, 'add_less' ), 20, 3 );
		remove_filter( 'siteorigin_widgets_blog_content_wrapper_styles', array( $this, 'adjust_content_wrapper_styles' ), 10, 2 );
	}

	public function add_less_variables( $less_vars, $instance, $widget ) {
		if ( empty( $instance['design']['related_title'] ) ) {
			return $less_vars;
		}

		if ( ! empty( $instance['design']['related_title']['font'] ) ) {
			$font = siteorigin_widget_get_font( $instance['design']['related_title']['font'] );
			$less_vars['related_title_font'] = $font['family'];

			if ( ! empty( $font['weight'] ) ) {
				$less_vars['related_title_font_style'] = $font['style'];
				$less_vars['related_title_font_weight'] = $font['weight_raw'];
			}
		}
		$less_vars['related_title_font_size'] = ! empty( $instance['design']['related_title']['font_size'] ) ? $instance['design']['related_title']['font_size'] : '16px';
		$less_vars['related_title_color'] = ! empty( $instance['design']['related_title']['color'] ) ? $instance['design']['related_title']['color'] : '';
		$less_vars['related_title_bottom_margin'] = ! empty( $instance['design']['related_title']['bottom_margin'] ) ? $instance['design']['related_title']['bottom_margin'] : '';

		if ( ! empty( $instance['design']['container']['margin'] ) ) {
			$container_margin = explode( ' ', $instance['design']['container']['margin'] );
			$less_vars['related_container_margin_top'] = $container_margin[0];
			$less_vars['related_container_margin_bottom'] = $container_margin[1];
		}

		return $less_vars;
	}

	public function add_less( $less, $vars, $instance ) {
		if ( empty( $instance['design']['related_title'] ) ) {
			return $less;
		}

		$less .= file_get_contents( plugin_dir_path( __FILE__ ) . 'less/related.less' );

		return $less;
	}

	public function adjust_content_wrapper_styles( $styles = array(), $settings = array() ) {
		if ( ! is_array( $styles ) ) {
			$styles = array();
		}

		$styles['padding'] = '20px 25px';

		return $styles;
	}

	public function metabox_options( $form_options ) {
		$settings = SiteOrigin_Premium_Options::single()->get_settings( 'plugin/related-posts' );

		if ( empty( $settings ) ) {
			return $form_options;
		}

		$post_types = $settings['types'] ?? array();
		$type_status = is_array( $post_types ) && in_array( get_post_type(), $post_types ) ? 'on' : 'off';

		$form_options['general']['fields']["related_posts_$type_status"] = array(
			'type' => 'checkbox',
			'label' => __( 'Display Related Posts', 'siteorigin-premium' ),
			'default' => $type_status === 'on',
		);

		return $form_options;
	}
}
