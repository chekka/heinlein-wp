<?php

/**
 * Class SiteOrigin_Premium_Utility
 *
 * This file contains utility functions that are used throughout the plugin.
 *
 */
class SiteOrigin_Premium_Utility {
	public static function single() {
		static $single;

		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Get a post type array.
	 *
	 * @see https://github.com/siteorigin/siteorigin-panels/blob/2.20.4/inc/settings.php#L742
	 *
	 * @return array
	 */
	public function get_post_types() {
		$types = get_transient( 'siteorigin_premium_post_types' );
		if ( empty( $types ) ) {
			$post_types = get_post_types( array( '_builtin' => false ) );

			$types = array(
				'page' => 'page',
				'post' => 'post',
			);

			// We don't use `array_merge` here as it will break things if a post type has a numeric slug.
			foreach ( $post_types as $key => $value ) {
				$types[ $key ] = $value;
			}

			unset( $types['ml-slider'] );
			unset( $types['shop_coupon'] );
			unset( $types['shop_order'] );
			unset( $types['so_mirror_widget'] );
			unset( $types['so_custom_post_type'] );

			foreach ( $types as $type_id => $type ) {
				$type_object = get_post_type_object( $type_id );

				if ( ! $type_object->show_ui ) {
					unset( $types[ $type_id ] );
					continue;
				}

				$types[ $type_id ] = $type_object->label;
			}

			set_transient( 'siteorigin_premium_post_types', $types, 24 * HOUR_IN_SECONDS );
		}

		return apply_filters( 'siteorigin_premium_post_types', $types );
	}

	/**
	 * Determines if an addon is enabled for the current post.
	 *
	 * @param array $settings The plugin settings.
	 * @return bool Returns true if the meta is enabled, false otherwise.
	 */
	public function is_addon_enabled_for_post( $settings, $setting ) {
		$premium_meta = get_post_meta( get_the_id(), 'siteorigin_premium_meta', true );

		// Check for global settings, and if this post type is enabled.
		if (
			! empty( $settings ) && !
			in_array( get_post_type(), $settings['types'] )
		) {
			if (
				empty( $premium_meta['general'] ) ||
				empty( $premium_meta['general'][ $setting . '_off'] )
			) {
				// Post has been disabled via meta.
				return false;
			}

			return true;
		}

		// Post type is enabled. Let's check if the user has disabled this specific post.
		if (
			empty( $premium_meta['general'] ) ||
			! empty( $premium_meta['general'][ $setting . '_on'] )
		) {
			return false;
		}

		return true;
	}
}
