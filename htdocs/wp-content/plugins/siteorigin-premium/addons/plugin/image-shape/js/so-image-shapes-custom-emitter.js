/* globals jQuery */

( function( $ ) {
	$( document ).on( 'shape_change', '.siteorigin-widget-field-type-image_shape', function( e, data ) {
		var $customField = data.field.parent().find( '.siteorigin-widget-field-shape_custom' );

		// We can't use a standard emitter for the custom shape field due to the below state checking.
		// To prevent it from appearing when it shouldn't, we control the visibility of the field by checking whether Shapes are enabled here.
		if ( ! $customField.data( 'setupForCustomShape' ) ) {
			data.field.parent().find( '.siteorigin-widget-field-enable .siteorigin-widget-input' ).on( 'change', function() {
				if ( ! $customField.data( 'setupForCustomShape' ) ) {
					$( this ).data( 'shape', data.field );
				}
	
				if (
					$( this ).is( ':checked' ) &&
					$( this ).data( 'shape' ).find( '.siteorigin-widget-shape-current .siteorigin-widget-shape-image' ).data( 'shape' ) == 'custom'
				) {
					$customField.show();
				} else {
					$customField.hide();
				}
			} ).trigger( 'change' );
			$customField.data( 'setupForCustomShape', true );
		}

		// When the user changes shape, adjust whether the custom image shape field shows.
		if ( data.shape == 'custom' ) {
			$customField.show();
		} else {
			$customField.hide();
		}
	} );

} )( jQuery );
