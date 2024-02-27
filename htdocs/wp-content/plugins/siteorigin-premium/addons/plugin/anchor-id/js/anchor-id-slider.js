/* globals jQuery, sowb */

var sowb = window.sowb || {};

jQuery( function( $ ) {
	var getSliderAnchorTags = function() {
		var anchors = {},
			possibleAnchors = [];
		if ( ! window.location.hash ) {
			return {};
		}

		// Are there mutiple anchors set?
		if ( window.location.hash.indexOf( ',' ) > -1 ) {
			possibleAnchors = window.location.hash.split( ',' );
		} else {
			possibleAnchors.push( window.location.hash );
		}

		for ( var i = 0; i < possibleAnchors.length; i++ ) {
			anchors[ possibleAnchors[ i ].replace( /[0-9#]/g, '' ) ] = possibleAnchors[ i ].replace( /[^0-9]/g, '' );
		}

		return anchors;
	};

	var anchors = getSliderAnchorTags();
	if ( ! $.isEmptyObject( anchors ) ) {
		// Update slides with the any anchor tags in the URL.
		$( '.sow-slider-images' ).on( 'slider_setup_after cycle-initialized', function() {
			setTimeout( function() {
				for ( var anchor in anchors ) {
					// Find the widget this is for.
					// The slice is to remove  the hyphen at the end of the string.
					var $anchor = $( '[data-anchor-id="' + anchor.slice( 0, -1 ) + '"]' );
					if ( $anchor.length ) {
						$anchor.cycle( 'goto', anchors[ anchor ] );
					}
				}
			}, 100 );
		} );

		// Scroll to the first slider anchor id.
		// The slice is to remove  the hyphen at the end of the string.
		var $anchor = $( '[data-anchor-id="' + Object.keys( anchors )[0].slice( 0, -1 ) + '"]' );
		if ( $anchor.length && typeof sowb.scrolled == 'undefined' ) {
			sowb.scrolled = true;
			// Add some magic number offset to make space for possible nav menus etc.
			var navOffset = soPremiumAnchorId.scrollto_offset ? soPremiumAnchorId.scrollto_offset : 90;
			setTimeout( function() {
				$( 'body, html' ).animate( {
					scrollTop: $anchor.parent().parent().offset().top - navOffset,
				}, 200 );
			}, 250 );
		}
	}

	$( '.sow-slider-images' ).each( function() {
		var $$ = $( this );
		var anchorId = $( this ).data( 'anchor-id' );
		// If this slider doesn't have an anchor set, don't set it up.
		if ( anchorId == undefined  ) {
			return;
		}

		$( this ).on( 'cycle-after', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
			anchors = getSliderAnchorTags();
			if ( $.isEmptyObject( anchors ) ) {
				window.location.hash = anchorId + '-' + optionHash.nextSlide;
			} else {
				// Remove the current anchor if the user navigated back to the first item.
				if ( optionHash.nextSlide == 0 ) {
					delete anchors[ anchorId + '-' ];
				} else {
					anchors[ anchorId + '-' ] = optionHash.nextSlide;
				}

				if ( $.isEmptyObject( anchors ) ) {
					window.history.pushState( '', document.title, window.location.pathname + window.location.search );
				} else {
					var hash = '';
					for ( var anchor in anchors ) {
						hash = hash + ( hash != '' ? ',' : '' ) + anchor + anchors[ anchor ];
					}
					window.location.hash = hash;
				}
			}
		} );
	} );
} );

window.sowb = sowb;
