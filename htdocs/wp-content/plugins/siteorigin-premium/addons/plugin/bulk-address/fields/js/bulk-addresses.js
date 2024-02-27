/* global jQuery, google */

( function( $ ) {

	var addBulkAddressLocation = function( address, $form, autocompleteService, checkAgain = false ) {
		autocompleteService.getQueryPredictions( { input: address }, function( predictions, status ) {
			if ( ! checkAgain ) {
				$form.find( '.siteorigin-widget-field-repeater-add' ).trigger( 'click' );
			}

			var $item = $form.find( '.siteorigin-widget-field-repeater-item' ).last(),
				$input = $item.find( '.siteorigin-widget-location-input' );

			if ( ! checkAgain ) {
				$item.find( '.siteorigin-widget-field-repeater-item-top' ).trigger( 'click' );
			}

			if ( status != google.maps.places.PlacesServiceStatus.OK || ! predictions ) {
				// Failed to find place. Add address as is.
				$item.find( '.siteorigin-widget-field-remove' ).trigger( 'click', { silent: true } );
				var checkAgain = prompt( soBulkAddressesField.error, address );
				if ( typeof checkAgain == 'string' ) {
					return addBulkAddressLocation( checkAgain, $form, autocompleteService, true );
				} else {
					return false;
				}
			}
			$input.val( predictions[0].description );
			sowbForms.LocationField().getSimplePlace( { name: predictions[0].description } )
			.then( function( simplePlace ) {
				$item.find( '.location-field-data' ).val( JSON.stringify( simplePlace ) );
				$input.val( predictions[1].description );
			} )
			.catch( function( status ) {
				console.warn( 'SiteOrigin Google Maps Widget: Geocoding failed for "' + predictions[0].description + '" with status: ' + status );
			} );
			return true;
		} );
	};

	$( document ).on( 'sowsetupformfield', '.siteorigin-widget-field-type-bulk_addresses', function( e ) {
		var $field = $( this );

		if ( $field.data( 'initialized' ) ) {
			return;
		}

		var $baseBtn = $( this ).find( '.so-bulk-addresses-field-add-bulk' ),
			$wrapper = $( this ).find( '.so-bulk-addresses-field-add-wrapper' ),
			$adder = $( this ).find( '.button-primary' ),
			$text = $( this ).find( 'textarea' );

		$baseBtn.on( 'click', function() {
			$baseBtn.hide();
			$wrapper.show();
		} );

		$text.on( 'keyup', function() {
			if ( $text.val() ) {
				$adder.removeClass( 'disabled' );
			} else {
				$adder.addClass( 'disabled' );
			}
		} );

		$adder.on( 'click', function() {
			var addresses = $text.val().split( "\n" ),
				$form = jQuery( '.siteorigin-widget-field-type-bulk_addresses' ).parents( '.siteorigin-widget-field-markers' );

			// To prevent items being added in reverse order, we need to reverse the addresses array. This is required due to the prioritisation Google does.
			addresses.reverse();
			var autocompleteService = new google.maps.places.AutocompleteService();
			for ( var i = 0; i < addresses.length; i++ ) {
				if ( addresses[ i ] == '' ) {
					continue;
				}
				addBulkAddressLocation( addresses[ i ], $form, autocompleteService );
			}

			// Reset.
			$text.val( '' );
			$adder.addClass( 'disabled' );
			$baseBtn.show();
			$wrapper.hide();
		} );

		$field.data( 'initialized', true );
	} );

} )( jQuery );
