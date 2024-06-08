<?php
/*
Plugin Name: SiteOrigin Block Animations
Description: Add animations to Page Builder rows, columns, and widgets, creating lively transitions that engage users with easy-to-set options.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/block-animations/
Tags: Page Builder
Video: 314963234
Requires: siteorigin-panels
*/

class SiteOrigin_Premium_Plugin_Animations {
	private $hidden_used = false;

	public function __construct() {
		add_filter( 'siteorigin_premium_addon_action_links-panels/animations', array( $this, 'action_links' ) );

		// Add the style groups.
		add_filter( 'siteorigin_panels_general_style_groups', array( $this, 'style_groups' ) );
		add_filter( 'siteorigin_panels_general_style_fields', array( $this, 'style_fields' ), 10, 3 );
		add_filter( 'siteorigin_panels_general_style_attributes', array( $this, 'style_attributes' ), 10, 2 );
		add_filter( 'siteorigin_panels_general_current_styles', array( $this, 'animation_type_migration' ), 10, 4 );

		add_action( 'wp_head', array( $this, 'add_hiding_class' ) );
		add_action( 'wp_footer', array( $this, 'add_hiding_class' ) );
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Add the action links.
	 */
	public function action_links( $links ) {
		$links[] = '<a href="#" target="_blank" rel="noopener noreferrer">' . __( 'Help', 'siteorigin-premium' ) . '</a>';

		return $links;
	}

	/**
	 * Add the animations style group
	 *
	 * @return mixed
	 */
	public function style_groups( $groups ) {
		$groups['animations'] = array(
			'name' => __( 'Animations', 'siteorigin-premium' ),
			'priority' => 20,
		);

		return $groups;
	}

	/**
	 * Add the animation style fields
	 *
	 * @return mixed
	 */
	public function style_fields( $fields, $post_id, $args ) {
		$fields['animation_type'] = array(
			'name' => __( 'Animation', 'siteorigin-premium' ),
			'type' => 'select',
			'options' => include SiteOrigin_Premium::dir_path() . 'inc/animation-types.php',
			'group' => 'animations',
			'description' => __( 'The type of animation for this element.', 'siteorigin-premium' ),
			'priority' => 5,
		);

		$animation_events = array(
			'enter' => __( 'Element Enters Screen', 'siteorigin-premium' ),
			'in'    => __( 'Element In Screen', 'siteorigin-premium' ),
			'load'  => __( 'Page Load', 'siteorigin-premium' ),
			'hover' => __( 'Hover', 'siteorigin-premium' ),
		);
		$default_event = 'enter';

		if ( ! empty( $args['builderType'] ) && $args['builderType'] === 'layout_slider_builder' ) {
			$animation_events[ 'slide_display' ] = __( 'Slide Display', 'siteorigin-premium' );
			$default_event = 'slide_display';
		}

		$fields['animation_event'] = array(
			'name' => __( 'Animation Event', 'siteorigin-premium' ),
			'type' => 'select',
			'options' => $animation_events,
			'default' => $default_event,
			'group' => 'animations',
			'description' => __( 'The event that triggers the animation.', 'siteorigin-premium' ),
			'priority' => 10,
		);

		if ( ! empty( $args['builderType'] ) && $args['builderType'] === 'layout_slider_builder' ) {
			$fields['animation_type_slide_out'] = array(
				'name' => __( 'Slide Out Animation', 'siteorigin-premium' ),
				'type' => 'select',
				'options' => include SiteOrigin_Premium::dir_path() . 'inc/animation-types.php',
				'group' => 'animations',
				'description' => __( 'This animation occurs every time the slide transitions away. Animation speed is the only setting that will be applied.', 'siteorigin-premium' ),
				'priority' => 12,
			);
		}

		$fields['animation_screen_offset'] = array(
			'name' => __( 'Screen Offset', 'siteorigin-premium' ),
			'type' => 'text',
			'default' => 0,
			'group' => 'animations',
			'description' => __( 'How many pixels above the bottom of the screen must the element be before animating in.', 'siteorigin-premium' ),
			'priority' => 15,
		);

		$fields['animation_duration'] = array(
			'name' => __( 'Animation Speed', 'siteorigin-premium' ),
			'type' => 'text',
			'default' => 1,
			'group' => 'animations',
			'description' => __( 'Number of seconds that the incoming animation lasts.', 'siteorigin-premium' ),
			'priority' => 20,
		);

		$fields['animation_repeat'] = array(
			'name' => __( 'Repeat Animation', 'siteorigin-premium' ),
			'type' => 'checkbox',
			'group' => 'animations',
			'description' => __( 'If the animation should repeat for the duration of the Animation Event. This is currently only implemented for the Hover Animation Event.', 'siteorigin-premium' ),
			'priority' => 25,
		);

		$fields['animation_hide'] = array(
			'name' => __( 'Hide Before Animation', 'siteorigin-premium' ),
			'type' => 'checkbox',
			'group' => 'animations',
			'default' => true,
			'description' => __( 'Hide the element before animating.', 'siteorigin-premium' ),
			'priority' => 30,
		);

		$fields['animation_disable_mobile'] = array(
			'name' => __( 'Disable Animation on Mobile', 'siteorigin-premium' ),
			'label' => __( 'Disabled', 'siteorigin-premium' ),
			'type' => 'checkbox',
			'group' => 'animations',
			'default' => false,
			'priority' => 30,
		);

		$fields['animation_state_end'] = array(
			'name' => __( 'Animation Final State', 'siteorigin-premium' ),
			'type' => 'select',
			'options' => array(
				'visible' => __( 'Visible', 'siteorigin-premium' ),
				'hidden' => __( 'Hidden', 'siteorigin-premium' ),
				'removed' => __( 'Removed', 'siteorigin-premium' ),
			),
			'group' => 'animations',
			'default' => 'visible',
			'description' => __( 'The final state of the element after the animation is complete.', 'siteorigin-premium' ),
			'priority' => 30,
		);

		$fields['animation_delay'] = array(
			'name' => __( 'Animation Delay', 'siteorigin-premium' ),
			'type' => 'text',
			'default' => 0,
			'group' => 'animations',
			'description' => __( 'Number of seconds after the event to start the animation.', 'siteorigin-premium' ),
			'priority' => 35,
		);

		$fields['animation_debounce'] = array(
			'name' => __( 'Animation Debounce', 'siteorigin-premium' ),
			'type' => 'text',
			'default' => 0.1,
			'group' => 'animations',
			'description' => __( 'Number of seconds to wait after the page stops scrolling before the animation is started. This only applies to the "Element Enters Screen" and "Element In Screen" events.', 'siteorigin-premium' ),
			'priority' => 35,
		);

		return $fields;
	}

	/**
	 * Migrate deprecated animations.
	 *
	 * @param $style array The currently selected styles.
	 * @param $post_id int The id of the current post.
	 * @param $type The current type of styles.
	 * @param $args array An array containing builder Arguments.
	 *
	 * @return array
	 */
	public function animation_type_migration( $style, $post_id, $type, $args ) {
		// Layout Slider.
		if (
			isset( $style['animation_type_slide_out'] ) &&
			! empty( $args['builderType'] ) &&
			$args['builderType'] === 'layout_slider_builder'
		) {
			$style['animation_type_slide_out'] = $this->style_migration_replacer( $style['animation_type_slide_out'] );
		}

		// All other usage.
		if ( isset( $style['animation_type'] ) ) {
			$style['animation_type'] = $this->style_migration_replacer( $style['animation_type'] );
		}

		return $style;
	}

	private function style_migration_replacer( $type ) {
		if ( $type == 'shake' ) {
			$type = 'shakeX';
		} elseif ( $type == 'lightSpeedIn' ) {
			$type = 'lightSpeedInRight';
		} elseif ( $type == 'lightSpeedOut' ) {
			$type = 'lightSpeedOutRight';
		}

		return $type;
	}

	/**
	 * @return array
	 */
	public function style_attributes( $attributes, $args ) {
		if ( ! empty( $args['animation_type'] ) || isset( $args[ 'animation_type_slide_out' ] ) ) {
			// We have an incoming animation
			$selector = preg_replace( '/\./', '', uniqid( 'animate-', true ) );
			$attributes['class'][] = $selector;

			$attributes['data-so-animation'] = array(
				'selector' => '.' . $selector,
				'animation' => $this->style_migration_replacer( $args['animation_type'] ),
				'breakpoint' => $this->get_mobile_width() . 'px',
				'duration' => isset( $args[ 'animation_duration' ] ) ? floatval( $args[ 'animation_duration' ] ) : 1,
				'repeat' => ! empty( $args[ 'animation_repeat' ] ) ? 1 : 0,
				'hide' => ! empty( $args[ 'animation_hide' ] ) ? 1 : 0,
				'disableAnimationMobile' => ! empty( $args[ 'animation_disable_mobile' ] ) ? 1 : 0,
				'finalState' => isset( $args[ 'animation_state_end' ] ) ? $args[ 'animation_state_end' ] : 'visible',
				'delay' => isset( $args[ 'animation_delay' ] ) ? floatval( $args[ 'animation_delay' ] ) : 0,
				'debounce' => isset( $args[ 'animation_debounce' ] ) ? floatval( $args[ 'animation_debounce' ] ) : 0,
				'event' => isset( $args[ 'animation_event' ] ) ? $args[ 'animation_event' ] : 'enter',
				'offset' => isset( $args[ 'animation_screen_offset' ] ) ? intval( $args[ 'animation_screen_offset' ] ) : 0,
			);

			// animation_type_slide_out will only be set if animation is for row/widget inside of SiteOrigin Layout Slider.
			if ( isset( $args[ 'animation_type_slide_out' ] ) ) {
				$attributes['data-so-animation']['animation_type_slide_out'] = $this->style_migration_replacer( $args['animation_type_slide_out'] );
			}

			$attributes['data-so-animation'] = json_encode( $attributes['data-so-animation'] );

			if ( ! empty( $args[ 'animation_hide' ] ) && ! empty( $args['animation_type'] ) ) {
				$this->hidden_used = true;
				$attributes['class'][] = 'panels-animation-hide';

				if ( ! empty( $args[ 'animation_disable_mobile' ] ) ) {
					$attributes['class'][] = 'panels-animation-hide-disable-mobile';
				}
			}

			$this->enqueue_scripts();
		}

		return $attributes;
	}

	public function get_mobile_width() {
		return (int) function_exists( 'siteorigin_panels_setting' ) ? siteorigin_panels_setting( 'mobile-width' ) : 768;
	}

	public function add_hiding_class() {
		static $once = false;

		if ( ! $once && $this->hidden_used ) {
			$once = true;
			?>
			<style type="text/css">.panels-animation-hide:not(.panels-animation-hide-disable-mobile){opacity:0} @media(min-width: <?php echo $this->get_mobile_width(); ?>px){.panels-animation-hide{opacity:0}}</style>
			<noscript><style type="text/css">.panels-animation-hide{opacity:1}</style></noscript>
			<?php
		}
	}

	public function enqueue_scripts() {
		// We'll need these scripts and styles
		wp_enqueue_script( 'siteorigin-premium-animate' );
		wp_enqueue_style( 'siteorigin-premium-animate' );
	}
}
