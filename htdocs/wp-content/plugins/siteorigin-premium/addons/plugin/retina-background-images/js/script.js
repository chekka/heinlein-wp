( function( $ ) {
	$( document ).on( 'setup_style_fields', function( e, view ) {
		var $background_image = view.$el.find( '.so-field-background_image_attachment' ),
			$retina_background_image = view.$el.find( '.so-field-retina_background_image' );
			$retina_background_image_size = view.$el.find( '.so-field-retina_background_image_size' );

		if ( $background_image.length && $retina_background_image.length ) {
			soRetinaBackgroundhandleVisibility(
				$background_image,
				'background_image_attachment',
				$retina_background_image
			);

			if ( $retina_background_image_size.length ) {
				soRetinaBackgroundhandleVisibility(
					$retina_background_image,
					'retina_background_image',
					$retina_background_image_size
				);
			}
		}

		function soRetinaBackgroundhandleVisibility( $image, name, $field ) {
			var handleVisibility = function() {
				var hasImage = $image.find( '[name="style[' + name + ']"]' );
				if ( ! hasImage.val() || hasImage.val() == 0 ) {
					hasImage = $image.find( '[name="style[' + name + '_fallback]"]' );
				}

				if ( hasImage.val() && hasImage.val() != 0 ) {
					$field.show();
				} else {
					$field.hide();
				}
			}
			handleVisibility();
			$image.find( '[name="style[background_image_attachment]"], [name="style[background_image_attachment_fallback]"]' ).on( 'change', handleVisibility );
			$image.find( '.remove-image' ).on( 'click', handleVisibility );
		}
	} );
} )( jQuery );
